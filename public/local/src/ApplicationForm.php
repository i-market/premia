<?php

namespace App;

use Bitrix\Main\Loader;
use Bitrix\Main\UserTable;
use CIBlock;
use CIBlockElement;
use CIBlockPropertyEnum;
use Core\Iblock as ib;
use Core\Underscore as _;
use Core\Nullable as nil;
use Core\Strings as str;
use CUser;

assert(Loader::includeModule('iblock'));

class ApplicationForm {
    const STATUS_ACCEPTED = 'ACCEPTED';
    const NOMINATION_GI_PROP_PREFIX = 'GI_';

    private static function associatedElement($iblockId, $userId) {
        $filter = array('IBLOCK_ID' => $iblockId, 'ACTIVE' => 'Y', 'PROPERTY_USER' => $userId);
        $select = array('*', 'PROPERTY_*');
        $result = (new CIBlockElement)->GetList(array('SORT' => 'ASC'), $filter, false, false, $select);
        return _::first(ib::collectElements($result));
    }

    static function all() {
        $el = new CIBlockElement();
        $nominationIds = array_filter(ApplicationForm::iblockIds(), function($iblockId) {
            return ApplicationForm::isNomination($iblockId);
        });
        $elements = array_reduce($nominationIds, function($acc, $iblockId) use ($el) {
            $filter = array_merge(array('IBLOCK_ID' => $iblockId), self::activeFilter());
            // TODO optimize
            $elements = ib::collectElements($el->GetList(array('SORT' => 'ASC'), $filter));
            return array_merge($acc, $elements);
        }, array());
        return $elements;
    }

    static function users($applications, $fields) {
        $userIds = array_map(function($item) {
            return $item['PROPERTIES']['USER']['VALUE'];
        }, $applications);
        $userFilter = array_merge(
            array('LOGIC' => 'OR'),
            array_map(function($userId) {
                return array('ID' => $userId);
            }, $userIds)
        );
        $result = UserTable::getList(array('select' => $fields, 'filter' => array($userFilter, 'ACTIVE' => 'Y')));
        $ret = array();
        while($x = $result->fetch()) {
            $ret[] = $x;
        }
        return $ret;
    }

    static function statusOptions() {
        $nominationIds = array_filter(ApplicationForm::iblockIds(), function($iblockId) {
            return ApplicationForm::isNomination($iblockId);
        });
        $iblockFilter = array_merge(
            array('LOGIC' => 'OR'),
            array_map(function($iblockId) {
                return array('IBLOCK_ID' => $iblockId);
            }, $nominationIds)
        );
        $props = ib::fetch(CIBlockPropertyEnum::GetList(array(), array($iblockFilter, 'CODE' => 'STATUS')));
        $options = array_unique(array_map(function($prop) {
            return _::pick($prop, array('XML_ID', 'VALUE'));
        }, $props), SORT_REGULAR);
        assert(
            count($options) === count(array_unique(array_map(function($prop) {
                return _::pick($prop, array('XML_ID'));
            }, $props), SORT_REGULAR)),
            'inconsistent status properties'
        );
        return $options;
    }

    static function iblockIds() {
        return array(
            'GENERAL_INFO' => Iblock::GENERAL_INFO,
            'SMALL_BUSINESS' => Iblock::SMALL_BUSINESS,
            'BREAKTHROUGH' => Iblock::BREAKTHROUGH,
            'ETP' => Iblock::ETP,
            'IMPORT_SUBSTITUTION' => Iblock::IMPORT_SUBSTITUTION,
            'SALES_INNOVATION' => Iblock::SALES_INNOVATION,
            'EXPORTER' => Iblock::EXPORTER,
            'SALES' => Iblock::SALES,
            'LAW' => Iblock::LAW
        );
    }

    static function nominationIblockIds() {
        return array_filter(self::iblockIds(), function($iblockId) {
            return self::isNomination($iblockId);
        });
    }

    static function activeFilter() {
        return array('ACTIVE' => 'Y', 'PROPERTY_AWARD' => App::getActiveAward());
    }

    static function isPersonalNomination($iblockId) {
        return in_array($iblockId, array(Iblock::SALES, Iblock::LAW));
    }

    static function voteIblockId($formIblockId) {
        $sharedKey = array_flip(self::iblockIds())[$formIblockId];
        return Vote::iblockIds()[$sharedKey];
    }

    // TODO refactor: very brittle way to do it
    static function isPublicProperty($iblockId, $propertyCode) {
        assert(in_array($iblockId, self::iblockIds()));
        $private = array('USER', 'FILES', 'STATUS');
        if (str::startsWith($propertyCode, self::NOMINATION_GI_PROP_PREFIX)) {
            return false;
        } else {
            return !in_array($propertyCode, $private);
        }
    }

    static function application($userId) {
        $ret = array();
        foreach (self::iblockIds() as $key => $iblockId) {
            $ret[$key] = self::associatedElement($iblockId, $userId);
        }
        return $ret;
    }

    static function isNomination($iblockId) {
        return in_array($iblockId, self::iblockIds()) && $iblockId !== Iblock::GENERAL_INFO;
    }

    private static function addOrUpdate($elementMaybe, $fields) {
        global $USER;
        $el = new CIBlockElement();
        // TODO refactor: ad-hoc
        $isNomination = $fields['IBLOCK_ID'] !== Iblock::GENERAL_INFO;
        $isAdd = $elementMaybe === null;
        if (isset($fields['DELETE_FILE_VALUES_FN']) && !$isAdd) {
            $deleteFileValues = $fields['DELETE_FILE_VALUES_FN']($elementMaybe['ID']);
            // mutate
            $fields = _::update($fields, 'PROPERTY_VALUES.FILES', function($files) use ($deleteFileValues) {
                return $files + $deleteFileValues;
            });
        }
//        $existingFiles = $elementMaybe === null ? array() : array_map(function($fileId) {
//            return CFile::GetFileArray($fileId);
//        }, $elementMaybe['PROPERTIES']['FILES']['VALUE']);
        // temporary duplicate files bug workaround
        // mutate
        $fields = _::update($fields, 'PROPERTY_VALUES.FILES', function($files) {
            $ret = _::uniqBy($files, function($file) {
                // dedupe by filename
                return _::get($file, 'VALUE.name');
            });
            if (count($files) !== count($ret)) {
                trigger_error('debugging duplicate filenames', E_USER_WARNING);
            }
            return $ret;
        });
        $result = $isAdd
            ? $el->Add($fields)
            : $el->Update($elementMaybe['ID'], $fields);
        $isSuccess = $result === true || is_int($result);
        // TODO refactor: inappropriate place for this?
        if ($isNomination && $isAdd && $isSuccess) {
            $nomination = CIBlock::GetByID($fields['IBLOCK_ID'])->GetNext();
            $event = array(
                'EMAIL' => $USER->GetEmail(),
                'NAME' => $USER->GetFormattedName(),
                'NOMINATION' => $nomination['~NAME']
            );
            App::sendMailEvent(MailEvent::NEW_NOMINATION, App::SITE_ID, $event);
        }
        return array(
            'isSuccess' => $isSuccess,
            'errorMessageMaybe' => $isSuccess ? null : $el->LAST_ERROR
        );
    }

    static function updateApplication($userId, $fields) {
        $results = _::mapValues(self::iblockIds(), function($iblockId, $key) use ($userId, $fields) {
            // TODO refactor missing iblocks
            return nil::map($fields[$key], function($fs) use ($iblockId, $userId) {
                return self::addOrUpdate(self::associatedElement($iblockId, $userId), $fs);
            });
        });
        return Api::reduceFormResults(array_values(_::clean($results)));
    }

    static function genericElementName($user, $formattedName) {
        return join(' - ', _::clean(array(
            $user['~WORK_COMPANY'],
            $formattedName
        )));
    }

    private static function userCompany($userId) {
        static $cache = array();
        if (array_key_exists($userId, $cache)) {
            return $cache[$userId];
        } else {
            $ret = CUser::GetByID($userId)->GetNext()['~WORK_COMPANY'];
            $cache[$userId] = $ret;
            return $ret;
        }
    }

    static function getDisplayName($application) {
        $appUserId = $application['PROPERTIES']['USER']['VALUE'];
        $company = self::userCompany($appUserId);
        if (self::isPersonalNomination(intval($application['IBLOCK_ID']))) {
            $fullName = str::ifEmpty(_::get($application, 'PROPERTIES.FULL_NAME.VALUE.TEXT'), null);
            $displayName = join(' / ', _::clean(array($company, $fullName)));
            return str::ifEmpty($displayName, 'Анкета № '.$application['ID']);
        } else {
            // generic default name just in case
            return str::ifEmpty($company, 'Анкета № '.$application['ID']);
        }
    }

    static function syncGeneralInfo($userId) {
        $el = new CIBlockElement();
        $filter = array_merge(
            array(
                'IBLOCK_ID' => Iblock::GENERAL_INFO,
                'PROPERTY_USER' => $userId
            ),
            ApplicationForm::activeFilter()
        );
        $generalInfo = _::first(ib::collectElements($el->GetList(array(), $filter)));
        $giProps = array_filter($generalInfo['PROPERTIES'], function($prop) {
            return self::isPublicProperty(Iblock::GENERAL_INFO, $prop['CODE']);
        });
        $iblockFilter = array_merge(
            array('LOGIC' => 'OR'),
            array_values(array_map(function($iblockId) {
                return array('IBLOCK_ID' => $iblockId);
            }, ApplicationForm::nominationIblockIds()))
        );
        $appFilter = array_merge(
            array(
                $iblockFilter,
                'PROPERTY_USER' => $userId
            ),
            ApplicationForm::activeFilter()
        );
        $appForms = ib::collectElements($el->GetList(array(), $appFilter));
        $results = array_map(function($appForm) use ($giProps, $el) {
            return array_map(function($prop) use ($appForm, $giProps, $el) {
                return $el->SetPropertyValueCode($appForm['ID'], self::NOMINATION_GI_PROP_PREFIX.$prop['CODE'], $prop['VALUE']['TEXT']);
            }, $giProps);
        }, $appForms);
        return $results;
    }
}
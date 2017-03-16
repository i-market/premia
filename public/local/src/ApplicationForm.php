<?php

namespace App;

use App\Iblock;
use Bitrix\Main\Loader;
use CIBlockElement;
use Core\Iblock as ib;
use Core\Underscore as _;
use Core\Nullable as nil;
use Core\Strings as str;
use CUser;

assert(Loader::includeModule('iblock'));

class ApplicationForm {
    const STATUS_ACCEPTED = 'ACCEPTED';

    private static function associatedElement($iblockId, $userId) {
        $filter = array('IBLOCK_ID' => $iblockId, 'ACTIVE' => 'Y', 'PROPERTY_USER' => $userId);
        $select = array('*', 'PROPERTY_*');
        $result = (new CIBlockElement)->GetList(array('SORT' => 'ASC'), $filter, false, false, $select);
        return _::first(ib::collectElements($result));
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
        return !in_array($propertyCode, $private);
    }

    static function application($userId) {
        $ret = array();
        foreach (self::iblockIds() as $key => $iblockId) {
            $ret[$key] = self::associatedElement($iblockId, $userId);
        }
        return $ret;
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
        $result = $isAdd
            ? $el->Add($fields)
            : $el->Update($elementMaybe['ID'], $fields);
        $isSuccess = $result === true || is_int($result);
        // TODO refactor: inappropriate place for this?
        if ($isNomination && $isAdd && $isSuccess) {
            $event = array(
                'EMAIL' => $USER->GetEmail(),
                'NAME' => $USER->GetFormattedName()
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
}
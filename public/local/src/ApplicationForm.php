<?php

namespace App;

use App\Iblock;
use Bitrix\Main\Loader;
use CIBlockElement;
use Core\Iblock as ib;
use Core\Underscore as _;
use Core\Nullable as nil;

assert(Loader::includeModule('iblock'));

class ApplicationForm {
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
            $user['WORK_COMPANY'],
            $formattedName
        )));
    }
}
<?php

namespace App;

use App\Iblock;
use Bitrix\Main\Loader;
use CIBlockElement;
use Core\Iblock as ib;
use Core\Underscore as _;

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
        $el = new CIBlockElement();
        $result = $elementMaybe === null
            ? $el->Add($fields)
            : $el->Update($elementMaybe['ID'], $fields);
        $isSuccess = $result === true || is_int($result);
        return array(
            'isSuccess' => $isSuccess,
            'errorMessageMaybe' => $isSuccess ? null : $el->LAST_ERROR
        );
    }

    static function updateApplication($userId, $fields) {
        $results = array_values(_::mapValues(self::iblockIds(), function($iblockId, $key) use ($userId, $fields) {
            return self::addOrUpdate(self::associatedElement($iblockId, $userId), $fields[$key]);
        }));
        return Api::reduceFormResults($results);
    }

    static function genericElementName($user, $formattedName) {
        return join(' - ', _::clean(array(
            $user['WORK_COMPANY'],
            $formattedName
        )));
    }
}
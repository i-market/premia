<?php

namespace App;

use App\Iblock;
use Bitrix\Main\Loader;
use CIBlockElement;
use Core\Iblock as ib;
use Core\Underscore as _;

assert(Loader::includeModule('iblock'));

class ApplicationForm {
    static function generalInfo($userId) {
        $filter = array('IBLOCK_ID' => Iblock::GENERAL_INFO, 'ACTIVE' => 'Y', 'PROPERTY_USER' => $userId);
        $select = array('*', 'PROPERTY_*');
        $result = (new CIBlockElement)->GetList(array('SORT' => 'ASC'), $filter, false, false, $select);
        return _::first(ib::collect($result));
    }

    static function updateGeneralInfo($userId, $fields) {
        $generalInfoMaybe = self::generalInfo($userId);
        if ($generalInfoMaybe === null) {
            return (new CIBlockElement)->Add($fields);
        } else {
            return (new CIBlockElement)->Update($generalInfoMaybe['ID'], $fields);
        }
    }

    static function genericElementName($user, $formattedName) {
        return join(' - ', _::clean(array(
            $user['WORK_COMPANY'],
            $formattedName
        )));
    }
}
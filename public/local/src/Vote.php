<?php

namespace App;

use Bitrix\Main\Loader;
use CIBlockElement;
use Core\View as v;
use Core\Iblock as ib;
use Core\Underscore as _;

assert(Loader::includeModule('iblock'));

class Vote {
    static function iblockIds() {
        return array(
            'SMALL_BUSINESS' => Iblock::VOTE_SMALL_BUSINESS
        );
    }

    // TODO refactor: very brittle way to do it
    static function isPublicProperty($iblockId, $propertyCode) {
        assert(in_array($iblockId, self::iblockIds()));
        $private = array('USER', 'FORM');
        return !in_array($propertyCode, $private);
    }

    static function votePath($iblockId, $elementId) {
        return v::path('auth/profile/vote/'.$iblockId.'/'.$elementId);
    }

    static function getByUser($userId, $iblockId, $filter) {
        $el = new CIBlockElement();
        $filterBase = array(
            'IBLOCK_ID' => $iblockId,
            'ACTIVE' => 'Y',
            'PROPERTY_USER' => $userId
        );
        $result = $el->GetList(array('SORT' => 'ASC'), array_merge($filterBase, $filter));
        return _::first(ib::collectElements($result));
    }
}
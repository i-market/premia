<?php

namespace App;

use Bitrix\Main\Loader;
use CIBlockElement;
use Core\View as v;
use Core\Iblock as ib;
use Core\Underscore as _;
use Core\Strings as str;

assert(Loader::includeModule('iblock'));

class Vote {
    static function iblockIds() {
        return array(
            'SMALL_BUSINESS' => Iblock::VOTE_SMALL_BUSINESS,
            'BREAKTHROUGH' => Iblock::VOTE_BREAKTHROUGH,
            'ETP' => Iblock::VOTE_ETP,
            'IMPORT_SUBSTITUTION' => Iblock::VOTE_IMPORT_SUBSTITUTION,
            'SALES_INNOVATION' => Iblock::VOTE_SALES_INNOVATION,
            'EXPORTER' => Iblock::VOTE_EXPORTER,
            'SALES' => Iblock::VOTE_SALES,
            'LAW' => Iblock::VOTE_LAW
        );
    }

    static function overallScore($element) {
        return array_reduce($element['PROPERTIES'], function($sum, $prop) use ($element) {
            if (self::isPublicProperty($element['IBLOCK_ID'], $prop['CODE'])) {
                return $sum + intval(str::ifEmpty($prop['VALUE'], 0));
            } else {
                return $sum;
            }
        }, 0);
    }

    // TODO refactor: very brittle way to do it
    // TODO refactor: more like isScoreProperty
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
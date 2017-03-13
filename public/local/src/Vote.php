<?php

namespace App;

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
}
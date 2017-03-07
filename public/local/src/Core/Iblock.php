<?php

namespace Core;

use Bitrix\Main\Loader;
use CIBlockResult;

assert(Loader::includeModule('iblock'));

class Iblock {
    /**
     * @param $ibResult CIBlockResult
     */
    static function collect($ibResult) {
        $ret = array();
        while($x = $ibResult->GetNext()) {
            $ret[] = $x;
        }
        return $ret;
    }

    /**
     * @param $ibResult CIBlockResult
     */
    static function collectElements($ibResult) {
        $ret = array();
        while($x = $ibResult->GetNextElement()) {
            $ret[] = array_merge($x->GetFields(), array(
                'PROPERTIES' => $x->GetProperties()
            ));
        }
        return $ret;
    }
}

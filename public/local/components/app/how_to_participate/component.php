<?php

use App\User;
use Core\Underscore as _;
use Core\Strings as str;

$arResult = array(
    'HEADING' => $arParams['HEADING'],
    'STEPS' => _::filter($arParams['STEPS'], function($item) {
        return !str::isEmpty($item);
    })
);
$this->IncludeComponentTemplate();

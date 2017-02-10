<?php

use App\Auth;
use Core\Underscore as _;
use Core\Strings as str;

$arResult = array(
    'HEADING' => $arParams['HEADING'],
    'SIGN_UP_PATH' => Auth::signupPath(),
    'STEPS' => _::filter($arParams['STEPS'], function($item) {
        return !str::isEmpty($item);
    })
);
$this->IncludeComponentTemplate();

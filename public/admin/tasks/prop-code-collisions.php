<?php
use App\Iblock;
use App\User;
use Core\Iblock as ib;
use Core\Underscore as _;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Раздел администратора");

if (User::ensureUserIsAdmin()) {
    $el = new CIBlockElement();
    $giProps = ib::collect(CIBlock::GetProperties(Iblock::GENERAL_INFO));
    echo '<pre>';
    foreach (\App\ApplicationForm::nominationIblockIds() as $iblockId) {
        $props = ib::collect(CIBlock::GetProperties($iblockId));
        echo $iblockId.PHP_EOL;
        echo var_export(array_reduce($props, function($acc, $prop) use ($giProps) {
            return _::set($acc, $prop['CODE'], in_array($prop['CODE'], _::pluck($giProps, 'CODE')));
        }, array()), false).PHP_EOL;
    }
    echo '</pre>';
//    var_export($giProps);
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");

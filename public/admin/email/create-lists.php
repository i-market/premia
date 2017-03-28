<?php
use App\Email;
use App\Iblock;
use App\User;
use Bitrix\Main\Loader;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Раздел администратора");

if (User::ensureUserIsAdmin()) {
    assert(Loader::includeModule('devstrong.subscribe'));
    $results = array();
    foreach (\App\ApplicationForm::iblockIds() as $iblockId) {
        if ($iblockId !== Iblock::GENERAL_INFO) {
            $iblockName = CIBlock::GetByID($iblockId)->GetNext()['NAME'];
            $results[] = (new CDsDeliveryList)->add(array(
                'ID' => $iblockId + 10,
                'NAME' => $iblockName
            ));
        }
    }
    $names = array(
        Email::NOMINEES => 'Участники премии',
        Email::COMPLETED => 'Анкета заполнена',
        Email::PARTIALLY_FILLED => 'Анкета не заполнена полностью',
        Email::ACCEPTED => 'Анкета допущена к конкурсу',
        Email::REJECTED => 'Анкета не допущена к конкурсу'
    );
    foreach ($names as $id => $name) {
        $results[] = (new CDsDeliveryList)->add(array(
            'ID' => $id,
            'NAME' => $name
        ));
    }
    var_dump($results);
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");

<?php
use App\App;
use App\ApplicationForm;
use App\User;
use Core\Iblock as ib;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Раздел администратора");

if (User::ensureUserIsAdmin()) {
    $propFieldsBase = array (
        'ID' => 0,
        'NAME' => 'Конкурс',
        'SORT' => 500,
        'CODE' => 'AWARD',
        'MULTIPLE' => 'N',
        'IS_REQUIRED' => 'Y',
        'ACTIVE' => 'Y',
        'USER_TYPE' => false,
        'PROPERTY_TYPE' => 'S',
//        'IBLOCK_ID' => 10,
        'FILE_TYPE' => '',
        'LIST_TYPE' => 'L',
        'ROW_COUNT' => 1,
        'COL_COUNT' => 30,
        'LINK_IBLOCK_ID' => 0,
        'DEFAULT_VALUE' => '',
        'USER_TYPE_SETTINGS' =>
            array (
            ),
        'WITH_DESCRIPTION' => 'N',
        'SEARCHABLE' => 'N',
        'FILTRABLE' => 'N',
        'MULTIPLE_CNT' => 5,
        'HINT' => '',
        'VALUES' =>
            array (
            ),
        'SECTION_PROPERTY' => 'Y',
        'SMART_FILTER' => 'N',
        'DISPLAY_TYPE' => false,
        'DISPLAY_EXPANDED' => 'N',
        'FILTER_HINT' => '',
    );
    $results = array();
    $el = new CIBlockElement();
    $activeAward = App::getActiveAward();

    foreach (ApplicationForm::iblockIds() as $iblockId) {
        $prop = new CIBlockProperty();
        $fields = array_merge($propFieldsBase,
            array(
                'IBLOCK_ID' => $iblockId
            )
        );
        $results[] = $prop->Add($fields);
    }

    $applications = array_reduce(ApplicationForm::iblockIds(), function($acc, $iblockId) use ($el) {
        $filter = array('IBLOCK_ID' => $iblockId);
        $elements = ib::collect($el->GetList(array('SORT' => 'ASC'), $filter));
        return array_merge($acc, $elements);
    }, array());

    foreach ($applications as $app) {
        CIBlockElement::SetPropertyValuesEx($app['ID'], $app['IBLOCK_ID'], array(
            'AWARD' => $activeAward
        ));
    }
    var_dump($results);
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");

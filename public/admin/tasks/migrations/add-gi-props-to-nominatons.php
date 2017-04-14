<?php
use App\ApplicationForm as af;
use App\Iblock;
use App\User;
use Core\Iblock as ib;
use Core\Underscore as _;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Раздел администратора");

if (User::ensureUserIsAdmin()) {
    ini_set('max_execution_time', 300);

    $el = new CIBlockElement();
    $results = array();

    $giProps = _::remove(_::keyBy(ib::collect(CIBlock::GetProperties(Iblock::GENERAL_INFO)), 'CODE'), 'USER');

    foreach (af::iblockIds() as $iblockId) {
        if (af::isNomination($iblockId)) {
            /// add props
            $fieldsBases = array_map(function($prop) {
                $base = array (
                    'ID' => 0,
//                    'NAME' => 'Описание деятельности компании, успешных контрактов, подаваемых на конкурс',
                    'SORT' => 500,
//                    'CODE' => 'TESTPROP',
                    'MULTIPLE' => 'N',
                    'IS_REQUIRED' => 'N',
                    'ACTIVE' => 'Y',
                    'USER_TYPE' => 'HTML',
                    'PROPERTY_TYPE' => 'S',
//                    'IBLOCK_ID' => 10,
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
                return array_merge($base, _::pick($prop, array('NAME', 'CODE')));
            }, $giProps);
            foreach ($fieldsBases as $fieldsBase) {
                $prop = new CIBlockProperty();
                $fields = array_merge($fieldsBase,
                    array(
                        'IBLOCK_ID' => $iblockId
                    )
                );
                $results[] = array('prop', $prop->Add($fields));
                assert($el->LAST_ERROR === '');
            }
            /// copy values
            $elements = ib::collectElements($el->GetList(array(), array('IBLOCK_ID' => $iblockId)));
            foreach ($elements as $element) {
                foreach ($giProps as $propCode => $prop) {
                    $userId = $element['PROPERTIES']['USER']['VALUE'];
                    $generalInfo = _::first(ib::collectElements($el->GetList(array(), array('IBLOCK_ID' => Iblock::GENERAL_INFO, 'PROPERTY_USER' => $userId))));
                    $value = $generalInfo['PROPERTIES'][$propCode]['VALUE'];
                    $results[] = array('value', $el->SetPropertyValueCode($element['ID'], $propCode, array('VALUE' => $value)));
                    assert($el->LAST_ERROR === '');
                }
            }
        }
    }

    var_export($results);
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");

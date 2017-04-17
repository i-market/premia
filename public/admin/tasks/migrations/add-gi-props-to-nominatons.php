<?php
use App\ApplicationForm as af;
use App\ApplicationForm;
use App\Iblock;
use App\User;
use Core\Iblock as ib;
use Core\Underscore as _;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Раздел администратора");

// TODO add with ApplicationForm::NOMINATION_GI_PROP_PREFIX
if (User::ensureUserIsAdmin()) {
    ini_set('max_execution_time', 300);

    $syncGeneralInfo = function($userId) {
        if (!$userId) {
            trigger_error('invalid arguments', E_USER_WARNING);
            return null;
        }
        $el = new CIBlockElement();
        $filter = array_merge(
            array(
                'IBLOCK_ID' => Iblock::GENERAL_INFO,
                'PROPERTY_USER' => $userId
            ),
            ApplicationForm::activeFilter()
        );
        $generalInfo = _::first(ib::collectElements($el->GetList(array(), $filter)));
        if ($generalInfo === null) {
            $iblockFilter = array_merge(
                array('LOGIC' => 'OR'),
                array_values(array_map(function ($iblockId) {
                    return array('IBLOCK_ID' => $iblockId);
                }, ApplicationForm::nominationIblockIds()))
            );
            $appFilter = array_merge(
                array(
                    $iblockFilter,
                    'PROPERTY_USER' => $userId
                ),
                ApplicationForm::activeFilter()
            );
            $appForms = ib::collectElements($el->GetList(array(), $appFilter));
            $results = array_map(function ($appForm) use ($el) {
                return array_map(function ($propCode) use ($appForm, $el) {
                    // cleanup incorrect values
                    return $el->SetPropertyValueCode($appForm['ID'], $propCode, '');
                }, ['GI_ACTIVITY', 'GI_ACHIEVEMENTS', 'GI_QUANTITY', 'GI_CLIENTS']);
            }, $appForms);
            return $results;
        } else return [];
    };

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
//                $results[] = array('prop', $prop->Add($fields));
                assert($el->LAST_ERROR === '');
            }
            /// copy values
            $elements = ib::collectElements($el->GetList(array(), array('IBLOCK_ID' => $iblockId)));
            foreach ($elements as $element) {
//                $results[] = \App\ApplicationForm::syncGeneralInfo($element['PROPERTIES']['USER']['VALUE']);
                // cleanup
                $results[] = $syncGeneralInfo($element['PROPERTIES']['USER']['VALUE']);
//                foreach ($giProps as $propCode => $prop) {
//                    $userId = $element['PROPERTIES']['USER']['VALUE'];
//                    $generalInfo = _::first(ib::collectElements($el->GetList(array(), array('IBLOCK_ID' => Iblock::GENERAL_INFO, 'PROPERTY_USER' => $userId))));
//                    $value = $generalInfo['PROPERTIES'][$propCode]['VALUE'];
//                    $results[] = array('value', $el->SetPropertyValueCode($element['ID'], af::NOMINATION_GI_PROP_PREFIX.$propCode, array('VALUE' => $value)));
//                    assert($el->LAST_ERROR === '');
//                }
            }
        }
    }

    var_export($results);
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");

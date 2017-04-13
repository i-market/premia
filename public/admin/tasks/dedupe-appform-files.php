<?php
use App\Iblock;
use App\User;
use Core\Iblock as ib;
use Core\Underscore as _;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Раздел администратора");

if (User::ensureUserIsAdmin()) {
    $el = new CIBlockElement();
    $results = array();
    foreach (\App\ApplicationForm::iblockIds() as $iblockId) {
        if ($iblockId !== Iblock::GENERAL_INFO) {
            $elements = ib::collectElements($el->GetList(array(), array('IBLOCK_ID' => $iblockId)));
            $results[$iblockId] = array_reduce($elements, function($acc, $element) use ($iblockId, $el) {
                $files = array_map(function($fileId) {
                    $file = CFile::GetFileArray($fileId);
                    return $file;
                }, $element['PROPERTIES']['FILES']['VALUE']);
                $sortedFiles = _::sort($files, 'TIMESTAMP_X', 'desc');
                // id → fingerprint
                $keep = array();
                $delete = array();
                foreach ($sortedFiles as $file) {
                    $fingerprint = array($file['ORIGINAL_NAME'], $file['FILE_SIZE']);
                    if (_::contains($keep, $fingerprint)) {
                        $delete[] = $file;
                    } else {
                        $keep[$file['ID']] = $fingerprint;
                    }
                }
                assert(count($keep) + count($delete) === count($files));
                $propCode = 'FILES';
                $props = ib::collect(CIBlockElement::GetProperty($iblockId, $element['ID'], 'sort', 'asc', array('CODE' => $propCode)));
//                $propValue = array_reduce(
//                    $props,
//                    function($values, $prop) use ($element, $iblockId, $delete) {
//                        return in_array($prop['VALUE'], _::pluck($delete, 'ID'))
//                            ? _::set($values, $prop['PROPERTY_VALUE_ID'], array('VALUE' => array('del' => 'Y')))
//                            : $values;
//                    },
//                    array()
//                );
                $result = array(
                    'delete_count' => count($delete)
                );
                if (!_::isEmpty($delete)) {
                    $value = array_reduce($delete, function($acc, $file) use ($props) {
                        $prop = _::find($props, function($prop) use ($file) {
                            return $prop['VALUE'] === $file['ID'];
                        });
                        return _::set($acc, $prop['PROPERTY_VALUE_ID'], array('VALUE' => array('del' => 'Y', 'MODULE_ID' => 'iblock')));
                    }, array());
                    $result['result'] = $el->SetPropertyValueCode($element['ID'], $propCode, $value);
                }
                return _::set($acc, $element['ID'], $result);
            }, array());
        }
    }
    var_export($results);
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");

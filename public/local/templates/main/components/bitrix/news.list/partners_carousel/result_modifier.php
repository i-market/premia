<?php

use Core\Underscore as _;

$arResult['ITEMS'] = array_map(function($item) {
    $resized = CFile::ResizeImageGet($item['PREVIEW_PICTURE'], array('width' => 190, 'height' => 60));
    return _::set($item, 'PREVIEW_PICTURE.RESIZED', $resized);
}, $arResult['ITEMS']);

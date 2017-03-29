<?php

use Core\View as v;

$pageContentMaxWidth = 1030;
$dimensions = array('width' => $pageContentMaxWidth, 'height' => $pageContentMaxWidth);
$arResult['ITEMS'] = v::assocResized($arResult['ITEMS'], 'PREVIEW_PICTURE', $dimensions);
$arResult['SECTION'] = $arResult['SECTION']['PATH'][0];
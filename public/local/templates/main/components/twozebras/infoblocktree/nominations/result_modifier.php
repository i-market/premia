<?php

$count = count(array_filter($arResult['ITEMS'], function($item) {
    return $item['DEPTH_LEVEL'] !== '1';
}));
$sections = array();
$curSectionIdx = null;
foreach ($arResult['ITEMS'] as $item) {
    if ($item['DEPTH_LEVEL'] === '1') {
        $curSectionIdx = count($sections);
        $sections[] = $item;
    } else {
        $sections[$curSectionIdx]['CHILDREN'][] = $item;
    }
}
$arResult['NOMINATION_COUNT'] = $count;
$arResult['SECTIONS'] = $sections;

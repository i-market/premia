<?php

use Hendrix\NewsListLike;
use Hendrix\Underscore as _;
use Hendrix\Strings as str;

$template = $this->getComponent()->getTemplate();
$items = array_map(function($item) use ($template) {
    $item['BX_ID'] = NewsListLike::addEditingActions($item, $template);
    $resized = CFile::ResizeImageGet($item['PREVIEW_PICTURE'], array('width' => 300, 'height' => 300));
    $item['PREVIEW_PICTURE'] = array_merge($item['PREVIEW_PICTURE'], $resized);
    $videoUrl = str::ifEmpty($item['PROPERTIES']['VIDEO_URL']['VALUE'], null);
    $item['PREVIEW_TYPE'] = $videoUrl === null ? 'foto' : 'video';
    return $item;
}, $arResult['ITEMS']);
$featured = _::take($items, 3);
$featured[1]['CLASS'] = 'hidden_second';
$featured[2]['CLASS'] = 'hidden_first';
$items[1]['CLASS'] = 'shown_second';
$items[2]['CLASS'] = 'shown_first';

$arResult = array(
    'ITEMS' => $items,
    'FEATURED' => $featured
);

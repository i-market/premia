<?php

use App\Video;
use Core\Underscore as _;

$arResult['ITEMS'] = array_map(function($item) {
    $url = _::get($item, 'PROPERTIES.EXTERNAL_URL.VALUE');
    return _::set($item, 'YOUTUBE_ID', Video::youtubeIdMaybe($url));
}, $arResult['ITEMS']);
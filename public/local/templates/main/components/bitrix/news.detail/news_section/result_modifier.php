<?php

use App\App;
use Hendrix\Null;
use Hendrix\Underscore as _;
use Hendrix\Strings as str;

$videoUrl = str::ifEmpty($arResult['PROPERTIES']['VIDEO_URL']['VALUE'], null);
$video = Null::map($videoUrl, function($url) {
    return array('YOUTUBE_ID' => App::youtubeVideoId($url));
});

$images = array_map(function($image) {
    // TODO dimensions
    $resized = CFile::ResizeImageGet($image, array('width' => 100, 'height' => 100));
    // TODO don't merge
    return array_merge($image, $resized);
}, $arResult['DISPLAY_PROPERTIES']['IMAGES']['FILE_VALUE']);
if ($video === null) {
    $arResult['FEATURED_IMAGE'] = _::first($images);
    $arResult['IMAGES'] = _::drop($images, 1);
} else {
    $arResult['IMAGES'] = $images;
}
$arResult['VIDEO'] = $video;

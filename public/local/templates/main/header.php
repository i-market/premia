<?php

use App\View;
use Bitrix\Main\Config\Configuration;
use Hendrix\App;
use Hendrix\Env;
use Hendrix\View as v;

$scripts = array(
    'js/vendor/scroll.js',
    'js/vendor/slick.min.js',
    'js/script.js',
    'js/main.js'
);
$menu = array_map(function($item) use (&$APPLICATION) {
    $item['is_active'] = $item['uri'] === $APPLICATION->GetCurDir();
    return $item;
}, array(
    'rent' => array('uri' => v::path('rent')),
    'for_customers' => array('uri' => v::path('for-customers'))
));
View::showLayoutHeader(array(
    'menu' => $menu,
    'scripts' => $scripts,
    'copyright_year' => date('Y'),
    'google_api_key' => Configuration::getValue('app')['google_api_key'],
    'google_maps_callback' => 'App.googleMapsCallback',
    'include_tracking_scripts' => App::env() !== Env::DEV
));

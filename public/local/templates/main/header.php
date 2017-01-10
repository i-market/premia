<?php

use App\View;
use Hendrix\App;
use Hendrix\Env;
use Hendrix\View as v;

$scripts = array(
    'js/vendor/scroll.js',
    'js/vendor/slick.min.js',
    'js/script.js'
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
    'include_tracking_scripts' => App::env() !== Env::DEV
));

<?php

use App\PageProperty;
use App\View;
use Bitrix\Main\Config\Configuration;
use Hendrix\App;
use Hendrix\Env;
use Hendrix\View as v;

?>
<!doctype html>
<html lang="<?= LANGUAGE_ID ?>">

<head>
    <? $APPLICATION->ShowHead() ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="format-detection" content="telephone=no" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <title><? $APPLICATION->ShowTitle() ?></title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800&amp;subset=cyrillic-ext" rel="stylesheet">
    <link rel="stylesheet" media="screen" href="<?= v::asset('css/lib/normalize.min.css') ?>">
    <link rel="stylesheet" media="screen" href="<?= v::asset('css/lib/slick.css') ?>">
    <link rel="stylesheet" media="screen" href="<?= v::asset('css/main.css') ?>">
    <!--[if gte IE 9]>
    <style type="text/css">
        .gradient {
            filter: none;
        }
    </style>
    <![endif]-->
    <!-- TODO tmp fixes -->
    <style>
        .main_menu ul li:nth-child(1),
        .main_menu ul li:nth-child(5) {
            visibility: hidden;
        }
        .main_menu ul li.logo {
            visibility: visible;
        }
    </style>
</head>
<body data-spy="scroll" data-target="#navbar" id="one">
<? $APPLICATION->ShowPanel() ?>
<?
$scripts = array(
    'js/vendor/scroll.js',
    'js/vendor/slick.min.js',
    'js/script.js',
    'js/vendor.js',
    'js/main.js'
);
$menu = array_map(function($item) use (&$APPLICATION) {
    $item['is_active'] = $item['uri'] === $APPLICATION->GetCurDir();
    return $item;
}, array(
    'rent' => array('uri' => v::path('rent')),
    'for_customers' => array('uri' => v::path('for-customers'))
));
View::showLayoutHeader(PageProperty::LAYOUT, 'base.twig', array(
    'menu' => $menu,
    'scripts' => $scripts,
    'form_specs' => \App\App::formSpecs(),
    'form_specs_json' => json_encode(\App\App::formSpecs()),
    'copyright_year' => date('Y'),
    'google_api_key' => Configuration::getValue('app')['google_api_key'],
    'google_maps_callback' => 'App.googleMapsCallback',
    'include_tracking_scripts' => App::env() !== Env::DEV
));

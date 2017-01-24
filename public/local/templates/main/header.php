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
    <link rel="stylesheet" media="screen" href="<?= v::asset('css/lib/jquery.fancybox.css') ?>">
    <link rel="stylesheet" media="screen" href="<?= v::asset('css/lib/slick.css') ?>">
    <link rel="stylesheet" media="screen" href="<?= v::asset('css/main.css') ?>">
    <!--[if gte IE 9]>
    <style type="text/css">
        .gradient {
            filter: none;
        }
    </style>
    <![endif]-->
</head>
<body data-spy="scroll" data-target="#navbar" id="one">
<? $APPLICATION->ShowPanel() ?>
<?
$scripts = array(
    'js/vendor/jquery.fancybox.pack.js',
    'js/vendor/scroll.js',
    'js/vendor/slick.min.js',
    'js/script.js',
    'js/vendor.js',
    // depends on mockup script.js
    'js/main.js'
);
$menu = array_map(function($item) use (&$APPLICATION) {
    $item['is_active'] = $item['uri'] === $APPLICATION->GetCurDir();
    return $item;
}, array(
    'homepage' => array('uri' => v::path('/')),
    'rent' => array('uri' => v::path('arendatoram')),
    'for_customers' => array('uri' => v::path('pokupatelyam'))
));
$contactsLatLng = array('lat' => 55.470993, 'lng' => 37.712681);
$domainName = explode(':', $_SERVER['HTTP_HOST'])[0];
$formSpecs = \App\App::formSpecs();
View::showLayoutHeader(PageProperty::LAYOUT, 'base.twig', array(
    'menu' => $menu,
    'scripts' => $scripts,
    'form_specs' => $formSpecs,
    'form_specs_json' => json_encode($formSpecs),
    'contacts_latlng' => $contactsLatLng,
    'contacts_geo_uri' => View::geoUri($contactsLatLng),
    'copyright_year' => date('Y'),
    'copyright_domain_name' => $domainName,
    'google_api_key' => Configuration::getValue('app')['google_api_key'],
    'google_maps_callback' => 'App.googleMapsCallback',
    'include_tracking_scripts' => App::env() !== Env::DEV
));

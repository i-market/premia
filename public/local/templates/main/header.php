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
    <link rel="stylesheet" media="screen" href="<?= v::asset('css/lib/vendor.css') ?>">
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
    'js/vendor.js',
    'js/bundle.js'
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
$formSpecs = \App\App::formSpecs();
View::showLayoutHeader(PageProperty::LAYOUT, 'base.twig', array(
    'menu' => $menu,
    'scripts' => $scripts,
    'form_specs' => $formSpecs,
    'form_specs_json' => json_encode($formSpecs),
    'contacts_latlng' => $contactsLatLng,
    'contacts_geo_uri' => View::geoUri($contactsLatLng),
    'include_tracking_scripts' => App::env() !== Env::DEV
));

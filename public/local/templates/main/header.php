<?php

use App\View;
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
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,800&amp;subset=cyrillic-ext" rel="stylesheet">
    <link rel="stylesheet" media="screen" href="<?= v::asset('lib/normalize.min.css') ?>">
    <link rel="stylesheet" media="screen" href="<?= v::asset('lib/slick.css') ?>">
    <link rel="stylesheet" media="screen" href="<?= v::asset('main.css') ?>">
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
    'vendor/scroll.js',
    'vendor/slick.min.js',
    'script.js'
);
$menu = array_map(function($item) use (&$APPLICATION) {
    $item['is_active'] = $item['uri'] === $APPLICATION->GetCurDir();
    return $item;
}, array(
    'rent' => array('uri' => SITE_DIR.'rent/'),
    'for_customers' => array('uri' => SITE_DIR.'for-customers/')
));
View::showLayoutHeader(array(
    'menu' => $menu,
    'scripts' => $scripts,
    'copyright_year' => date('Y'),
    'include_tracking_scripts' => App::env() !== Env::DEV
));

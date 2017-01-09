<?php

use App\View;
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
    <link rel="stylesheet" media="screen" href="<?= View::assetUri('lib/normalize.min.css') ?>">
    <link rel="stylesheet" media="screen" href="<?= View::assetUri('lib/slick.css') ?>">
    <link rel="stylesheet" media="screen" href="<?= View::assetUri('main.css') ?>">
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
View::showLayoutHeader(array(
    'script_uris' => array_map('App\View::assetUri', $scripts),
    'copyright_year' => date('Y')
));

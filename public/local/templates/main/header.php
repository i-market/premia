<?php
use App\App;
use Core\View as v;
?>
<!doctype html>
<html lang="<?= LANGUAGE_ID ?>">
<head>
    <? $APPLICATION->ShowHead() ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="format-detection" content="telephone=no" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <title><? $APPLICATION->ShowTitle() ?></title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700&amp;subset=cyrillic-ext" rel="stylesheet">
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
<body>
<? $APPLICATION->ShowPanel() ?>
<?= \Core\View::showLayoutHeader('layout', 'base.twig', App::layoutContext()) ?>

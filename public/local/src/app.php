<?php

namespace App;

use Hendrix\View as v;
use Maximaster\Tools\Twig\TemplateEngine;

class Iblock {
    const PRODUCT_CATEGORIES_ID = 2;
}

class View {
    static private $footer = null;

    static function showLayoutHeader($pageProperty, $defaultLayout, $context) {
        v::showForProperty($pageProperty, function($path) use ($context) {
            $twig = TemplateEngine::getInstance()->getEngine();
            $placeholder = '<page-placeholder/>';
            $ctx = array_merge(array('page' => $placeholder), $context);
            $html = $twig->render(SITE_TEMPLATE_PATH.'/layouts/'.$path, $ctx);
            $parts = explode($placeholder, $html);
            $header = $parts[0];
            $footer = $parts[1];
            self::$footer = $footer;
            echo $header;
        }, $defaultLayout);
    }

    static function showLayoutFooter() {
        global $APPLICATION;
        $APPLICATION->AddBufferContent(function() {
            assert(self::$footer !== null);
            return self::$footer;
        });
    }

    static function twig() {
        return TemplateEngine::getInstance()->getEngine();
    }
}
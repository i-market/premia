<?php

namespace App;

use Maximaster\Tools\Twig\TemplateEngine;

class View {
    static private $footer = null;

    // TODO refactor layout rendering
    // TODO cache layout
    static function showLayoutHeader($context) {
        // TODO get from page property
        $path = 'base.twig';
        $twig = TemplateEngine::getInstance()->getEngine();
        // {{ page }}, context.page = some placeholder string
        $placeholder = '<work-area/>';
        $ctx = array_merge(array('page' => $placeholder), $context);
        $html = $twig->render(SITE_TEMPLATE_PATH.'/layouts/'.$path, $ctx);
        $parts = explode($placeholder, $html);
        $header = $parts[0];
        $footer = $parts[1];
        self::$footer = $footer;
        echo $header;
    }

    static function showLayoutFooter() {
        assert(self::$footer !== null);
        echo self::$footer;
    }

    static function twig() {
        return TemplateEngine::getInstance()->getEngine();
    }
}
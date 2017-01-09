<?php

namespace App;

use Hendrix\App;
use Hendrix\Env;
use Hendrix\Strings as str;
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

    // TODO refactor
    static function asset($path) {
        if (str::startsWith($path, 'images')) {
           return SITE_TEMPLATE_PATH.'/'.$path;
        }
        // TODO prod assets
        // TODO move paths to config
        return App::isEnv(Env::DEV) ? SITE_TEMPLATE_PATH.'/dev/'.$path : 'TODO';
    }

    static function twig() {
        return TemplateEngine::getInstance()->getEngine();
    }
}
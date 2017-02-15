<?php

namespace Core;

use Bitrix\Main\Config\Configuration;
use CBitrixComponentTemplate;
use CFile;
use CIBlock;
use Core\Underscore as _;
use Core\View as v;
use Maximaster\Tools\Twig\TemplateEngine;
use Underscore\Methods\ArraysMethods;
use Underscore\Methods\StringsMethods;

class Underscore extends ArraysMethods {
    static function map($array, $f) {
        $ret = array();
        foreach ($array as $k => $v) {
            $ret[$k] = $f($v, $k);
        }
        return $ret;
    }

    static function filter($array, $pred = null) {
        // restore indices
        return array_values(array_filter($array, $pred));
    }
    
    static function drop($array, $n) {
        return array_slice($array, $n);
    }

    static function take($array, $n) {
        return array_slice($array, 0, $n);
    }

    static function identity() {
        return function($x) {
            return $x;
        };
    }

    static function constantly($x) {
        return function() use ($x) {
            return $x;
        };
    }

    static function noop() {}
}

class Null {
    static public function get($nullable, $default) {
        return $nullable === null ? $default : $nullable;
    }

    static public function map($nullable, $f) {
        return $nullable !== null ? $f($nullable) : $nullable;
    }
}

class Strings extends StringsMethods {
    static function isEmpty($str) {
        return trim($str) === '';
    }

    static function ifEmpty($str, $value) {
        return self::isEmpty($str) ? $value : $str;
    }

    static function contains($s, $subString) {
        return strpos($s, $subString) !== false;
    }

    static function replaceAll($s, $pattern, $replacement) {
        while(preg_match($pattern, $s))
            $s = preg_replace($pattern, $replacement, $s);
        return $s;
    }
}

class Env {
    const DEV = "dev";
    const STAGE = "stage";
    const PROD = "prod";
}

class App {
    static function env() {
        $app = Null::get(Configuration::getValue('app'), array());
        return _::get($app, 'env', Env::PROD);
    }
}

class View {
    private static $assetManifest = null;
    private static $footer = null;

    static function asset($path) {
        $build = SITE_TEMPLATE_PATH.'/build';
        if (App::env() === Env::DEV) {
            return $build.'/assets/'.$path;
        } else {
            if (self::$assetManifest === null) {
                $manifestFile = $_SERVER['DOCUMENT_ROOT'].$build.'/rev/rev-manifest.json';
                self::$assetManifest = json_decode(file_get_contents($manifestFile), true);
            }
            if (isset(self::$assetManifest[$path])) {
                return $build.'/rev/'.self::$assetManifest[$path];
            } else {
                return $build.'/assets/'.$path;
            }
        }
    }

    static function showForProperty($propName, $f, $defaultVal = null) {
        global $APPLICATION;
        $APPLICATION->AddBufferContent(function() use ($propName, $defaultVal, $f, &$APPLICATION) {
            $propVal = $APPLICATION->GetProperty($propName, $defaultVal);
            ob_start();
            if ($propVal !== false) {
                $f($propVal);
            }
            return ob_get_clean();
        });
    }

    static function showLayoutHeader($pageProperty, $defaultLayout, $context) {
        v::showForProperty($pageProperty, function($layout) use ($context) {
            $path = is_array($layout) ? $layout[0] : $layout;
            $propCtx = is_array($layout) ? $layout[1] : array();
            $twig = TemplateEngine::getInstance()->getEngine();
            $placeholder = '<page-placeholder/>';
            $ctx = array_merge(array('page' => $placeholder), $context, $propCtx);
            $html = $twig->render(SITE_TEMPLATE_PATH.'/layouts/'.$path, $ctx);
            list($header, $footer) = explode($placeholder, $html);
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

    static function partial($path) {
        return SITE_TEMPLATE_PATH.'/partials/'.$path;
    }

    static function path($path) {
        // TODO ad-hoc
        if ($path === '/') return SITE_DIR;
        return SITE_DIR.$path.'/';
    }

    static function includedArea($path) {
        return SITE_DIR.'include/'.$path;
    }

    static function layout($path) {
        return SITE_TEMPLATE_PATH.'/layouts/'.$path;
    }

    static function renderIncludedArea($path, $options = array()) {
        global $APPLICATION;
        $opts = array_merge(array(
            'TEMPLATE' => '.default',
            'PARAMS' => array()
        ), $options);
        ob_start();
        $APPLICATION->IncludeComponent(
            "bitrix:main.include",
            $opts['TEMPLATE'],
            Array(
                "AREA_FILE_SHOW" => "file",
                "PATH" => v::includedArea($path)
            ),
            null,
            $opts['PARAMS']
        );
        return ob_get_clean();
    }

    static function assocResized($items, $key, $dimensions) {
        return array_map(function($item) use ($key, $dimensions) {
            $resized = CFile::ResizeImageGet($item[$key], $dimensions);
            return _::set($item, $key.'.RESIZED', $resized);
        }, $items);
    }
}

class Form {
    static function field($name, $label, $type = 'text') {
        return array(
            'name' => $name,
            'type' => $type,
            'label' => $label
        );
    }

    // TODO label?
    static function select($name, $label, $options) {
        return array(
            'name' => $name,
            'label' => $label,
            'type' => 'select',
            'options' => array_map(function($option) {
                return is_array($option) ? $option : array(
                    'text' => $option,
                    'value' => $option,
                    'attributes' => array()
                );
            }, $options)
        );
    }
}

class NewsListLike {
    /**
     * @param array $element
     * @param CBitrixComponentTemplate $template
     * @return string dom element id
     */
    static function addEditingActions($element, $template) {
        assert(array_key_exists('EDIT_LINK', $element));
        assert(array_key_exists('DELETE_LINK', $element));
        $template->AddEditAction($element['ID'], $element['EDIT_LINK'],
            CIBlock::GetArrayByID($element['IBLOCK_ID'], 'ELEMENT_EDIT'));
        $template->AddDeleteAction($element['ID'], $element['DELETE_LINK'],
            CIBlock::GetArrayByID($element['IBLOCK_ID'], 'ELEMENT_DELETE'),
            array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        return $template->GetEditAreaId($element['ID']);
    }
}

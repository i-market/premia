<?php

namespace Hendrix;

use Bitrix\Main\Config\Configuration;
use Hendrix\Underscore as _;

class Underscore {
    static function get($array, $key, $default = null) {
        return $default !== null && !array_key_exists($key, $array) ? $default : $array[$key];
    }

    static function drop($array, $n) {
        return array_slice($array, $n);
    }
}

class Null {
    static public function get($nullable, $default) {
        return $nullable === null ? $default : $nullable;
    }
    static public function map($nullable, $f) {
        return $nullable !== null ? $f($nullable) : $nullable;
    }
}

class Strings {
    // TODO copy-pasta
    public static function startsWith($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {
            if ($needle !== '' && strpos($haystack, $needle) === 0) {
                return true;
            }
        }

        return false;
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

    static function isEnv() {
        return in_array(self::env(), func_get_args(), true);
    }
}

class View {
    private static $assetManifest = null;

    static function asset($path) {
        $build = SITE_TEMPLATE_PATH.'/build';
        if (App::isEnv(Env::DEV)) {
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

    static function partial($path) {
        return SITE_TEMPLATE_PATH.'/partials/'.$path;
    }

    static function path($path) {
        return SITE_DIR.$path.'/';
    }

    static function includedArea($path) {
        return SITE_DIR.'include/'.$path;
    }
}

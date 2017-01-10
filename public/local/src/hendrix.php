<?php

namespace Hendrix;

use Hendrix\Underscore as _;

class Underscore {
    static function get($array, $key, $default = null) {
        return $default !== null && !array_key_exists($key, $array) ? $default : $array[$key];
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
        return _::get($_ENV, 'APP_ENV', Env::PROD);
    }

    static function isEnv() {
        return in_array(self::env(), func_get_args(), true);
    }
}

class View {
    private static $assetManifest = null;

    static function asset($path) {
        if (App::isEnv(Env::DEV)) {
            return SITE_TEMPLATE_PATH.'/assets/'.$path;
        } else {
            if (self::$assetManifest === null) {
                $assetsPath = $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/assets';
                self::$assetManifest = json_decode(file_get_contents($assetsPath.'/rev-manifest.json'), true);
            }
            $revPath = _::get(self::$assetManifest, $path, $path);
            return SITE_TEMPLATE_PATH.'/assets/'.$revPath;
        }
    }

    static function partial($path) {
        return SITE_TEMPLATE_PATH.'/partials/'.$path;
    }

    static function path($path) {
        return SITE_DIR.$path.'/';
    }
}
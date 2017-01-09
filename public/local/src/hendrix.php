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

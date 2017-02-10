<?php

namespace App;

use Core\View as v;

class App {
    static function layoutContext() {
        return array(
            'main_menu' => self::renderMainMenu(),
            'auth_modal' => Auth::renderAuthForm(),
            'signup_path' => Auth::signupPath(),
            'profile_path' => Auth::profilePath()
        );
    }

    static function renderMainMenu() {
        global $APPLICATION;
        ob_start();
        $APPLICATION->IncludeComponent(
            "bitrix:menu",
            "main",
            Array(
                "ALLOW_MULTI_SELECT" => "N",
                "CHILD_MENU_TYPE" => "left",
                "DELAY" => "N",
                "MAX_LEVEL" => "2",
                "MENU_CACHE_GET_VARS" => array(""),
                "MENU_CACHE_TIME" => "3600",
                "MENU_CACHE_TYPE" => "N",
                "MENU_CACHE_USE_GROUPS" => "Y",
                "ROOT_MENU_TYPE" => "top",
                "USE_EXT" => "Y"
            )
        );
return ob_get_clean();
    }
}

class Auth {
    static function profilePath() {
        return v::path('user/profile');
    }

    static function signupPath() {
        return v::path('user/signup');
    }

    static function renderAuthForm() {
        global $APPLICATION;
        ob_start();
        $APPLICATION->IncludeComponent(
            "bitrix:system.auth.form",
            "",
            Array(
                "FORGOT_PASSWORD_URL" => v::path('user/reset'),
                "PROFILE_URL" => self::profilePath(),
                "REGISTER_URL" => self::signupPath(),
                "SHOW_ERRORS" => "Y"
            )
        );
        return ob_get_clean();
    }
}

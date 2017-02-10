<?php

namespace App;

use Core\View as v;

class App {
    static function layoutContext() {
        return array(
            'auth_modal' => Auth::renderAuthForm(),
            'signup_path' => Auth::signupPath(),
            'profile_path' => Auth::profilePath()
        );
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

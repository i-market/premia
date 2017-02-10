<?php

namespace App;

use Core\View as v;

class App {
    static function layoutContext() {
        return array(
            'auth_modal' => Auth::renderAuthForm()
        );
    }
}

class Auth {
    static function profilePath() {
        return v::path('auth/profile');
    }

    static function renderAuthForm() {
        global $APPLICATION;
        ob_start();
        $APPLICATION->IncludeComponent(
            "bitrix:system.auth.form",
            "",
            Array(
                "FORGOT_PASSWORD_URL" => v::path('auth/reset'),
                "PROFILE_URL" => self::profilePath(),
                "REGISTER_URL" => v::path('auth/signup'),
                "SHOW_ERRORS" => "Y"
            )
        );
        return ob_get_clean();
    }
}

<?php

namespace App;

use Core\View as v;

class EventHandlers {
    static function onBeforeUserUpdate(&$fields) {
        // email as login
        // https://dev.1c-bitrix.ru/community/webdev/user/17138/blog/1651/
        // TODO check if admin
//        $fields['LOGIN'] = $fields['EMAIL'];
        return $fields;
    }

    static function onAfterUserLogout(&$params) {
        LocalRedirect(v::path('/'));
        return $params;
    }

    private static function ref($name) {
        return '\App\EventHandlers::'.$name;
    }

    static function listen() {
        AddEventHandler('main', 'OnBeforeUserRegister', self::ref('onBeforeUserUpdate'));
        AddEventHandler('main', 'OnBeforeUserUpdate', self::ref('onBeforeUserUpdate'));
        AddEventHandler('main', 'OnAfterUserLogout', self::ref('onAfterUserLogout'));
    }
}
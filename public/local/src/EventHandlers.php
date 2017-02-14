<?php

namespace App;

class EventHandlers {
    static function onBeforeUserUpdate(&$fields) {
        // email as login
        // https://dev.1c-bitrix.ru/community/webdev/user/17138/blog/1651/
        $fields['LOGIN'] = $fields['EMAIL'];
        return $fields;
    }

    private static function ref($name) {
        return '\App\EventHandlers::'.$name;
    }

    static function listen() {
        AddEventHandler('main', 'OnBeforeUserRegister', self::ref('onBeforeUserUpdate'));
        AddEventHandler('main', 'OnBeforeUserUpdate', self::ref('onBeforeUserUpdate'));
    }
}
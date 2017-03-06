<?php

namespace App;

use Core\View as v;
use CUser;
use Core\Iblock as ib;
use Core\Underscore as _;

class EventHandlers {
    static function onBeforeUserUpdate(&$fields) {
        // TODO check for the appropriate user group instead
        $adminGroup = 1;
        $groups = array_map(function($group) {
            return intval($group['GROUP_ID']);
        }, ib::collect(CUser::GetUserGroupList($fields['ID'])));
        $isNotAdmin = !_::contains($groups, $adminGroup);
        if ($isNotAdmin) {
            // email as login
            // https://dev.1c-bitrix.ru/community/webdev/user/17138/blog/1651/
            $fields['LOGIN'] = $fields['EMAIL'];
        }
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
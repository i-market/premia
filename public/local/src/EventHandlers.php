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

    static function onAfterUserAdd(&$fieldsRef) {
        $groupIds = _::mapValues($fieldsRef['GROUP_ID'], 'GROUP_ID');
        $isExpert = in_array(User::EXPERT_GROUP, $groupIds);
        if ($isExpert) {
            $msg = '';
            $eventName = 'NEW_EXPERT';
            // when expert is added by an admin, send a password reset link
            CUser::SendUserInfo($fieldsRef['ID'], App::SITE_ID, $msg, false, $eventName);
        }
        return $fieldsRef;
    }

    private static function ref($name) {
        return '\App\EventHandlers::'.$name;
    }

    static function listen() {
        AddEventHandler('main', 'OnBeforeUserRegister', self::ref('onBeforeUserUpdate'));
        AddEventHandler('main', 'OnBeforeUserUpdate', self::ref('onBeforeUserUpdate'));
        AddEventHandler('main', 'OnAfterUserLogout', self::ref('onAfterUserLogout'));
        AddEventHandler('main', 'OnAfterUserAdd', self::ref('onAfterUserAdd'));
    }
}
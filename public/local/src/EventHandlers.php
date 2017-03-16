<?php

namespace App;

use CIBlockElement;
use Core\View as v;
use CUser;
use Core\Iblock as ib;
use Core\Underscore as _;

class EventHandlers {
    static function onBeforeUserUpdate(&$fieldsRef) {
        // TODO check for the appropriate user group instead
        $adminGroup = 1;
        $groups = array_map(function($group) {
            return intval($group['GROUP_ID']);
        }, ib::collect(CUser::GetUserGroupList($fieldsRef['ID'])));
        $isNotAdmin = !_::contains($groups, $adminGroup);
        if ($isNotAdmin) {
            // email as login
            // https://dev.1c-bitrix.ru/community/webdev/user/17138/blog/1651/
            $fieldsRef['LOGIN'] = $fieldsRef['EMAIL'];
        }
        return $fieldsRef;
    }

    static function onAfterUserLogout($params) {
        LocalRedirect(v::path('/'));
        return $params;
    }

    static function onAfterUserAdd($fields) {
        $groupIds = _::mapValues($fields['GROUP_ID'], 'GROUP_ID');
        $isExpert = in_array(User::EXPERT_GROUP, $groupIds);
        if ($isExpert) {
            $msg = '';
            $eventName = 'NEW_EXPERT';
            // when expert is added by an admin, send a password reset link
            CUser::SendUserInfo($fields['ID'], App::SITE_ID, $msg, false, $eventName);
        }
        return $fields;
    }

    static function onUserDelete($userId) {
        $isExpert = User::isInGroup($userId, User::EXPERT_GROUP);
        if ($isExpert) {
            $iblockFilters = array_map(function($id) {
                return array('IBLOCK_ID' => $id);
            }, array_values(Vote::iblockIds()));
            $filter = array('PROPERTY_USER' => $userId, array_merge(array('LOGIC' => 'OR'), $iblockFilters));
            $result = (new CIBlockElement)->GetList(array(), $filter, false, false, array('ID'));
            $elementIds = _::mapValues(ib::collect($result), 'ID');
            $results = array_map(function($id) {
                return CIBlockElement::Delete($id);
            }, $elementIds);
        }
        return $userId;
    }

    private static function ref($name) {
        return '\App\EventHandlers::'.$name;
    }

    static function listen() {
        AddEventHandler('main', 'OnBeforeUserRegister', self::ref('onBeforeUserUpdate'));
        AddEventHandler('main', 'OnBeforeUserUpdate', self::ref('onBeforeUserUpdate'));
        AddEventHandler('main', 'OnAfterUserLogout', self::ref('onAfterUserLogout'));
        AddEventHandler('main', 'OnAfterUserAdd', self::ref('onAfterUserAdd'));
        AddEventHandler('main', 'OnUserDelete', self::ref('onUserDelete'));
    }
}
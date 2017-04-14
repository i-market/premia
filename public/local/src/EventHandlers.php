<?php

namespace App;

use CIBlockElement;
use Core\View as v;
use CUser;
use Core\Iblock as ib;
use Core\Underscore as _;
use Core\Nullable as nil;

class EventHandlers {
    private static function ref($name) {
        return '\App\EventHandlers::'.$name;
    }

    static function listen() {
        AddEventHandler('main', 'OnBeforeUserRegister', self::ref('onBeforeUserUpdate'));
        AddEventHandler('main', 'OnBeforeUserUpdate', self::ref('onBeforeUserUpdate'));
        AddEventHandler('main', 'OnAfterUserLogout', self::ref('onAfterUserLogout'));
        AddEventHandler('main', 'OnAfterUserAdd', self::ref('onAfterUserAdd'));
        AddEventHandler('main', 'OnAfterSetUserGroup', self::ref('onAfterSetUserGroup'));
        AddEventHandler('main', 'OnUserDelete', self::ref('onUserDelete'));
        AddEventHandler('iblock', 'OnAfterIBlockElementAdd', self::ref('onAfterIBlockElementAdd'));
        AddEventHandler('iblock', 'OnBeforeIBlockElementUpdate', self::ref('onBeforeIBlockElementUpdate'));
        AddEventHandler('iblock', 'OnAfterIBlockElementUpdate', self::ref('onAfterIBlockElementUpdate'));
    }

    private static function onNomineeAdded($userId) {
        if (App::emailSubscriptionsEnabled()) {
            Util::warnOnException(function () use ($userId) {
                Email::addUsersToList(Email::NOMINEES, array(CUser::GetByID($userId)->GetNext()));
            });
        }
    }

    private static function onApplicationAdded($fields) {
        if (App::emailSubscriptionsEnabled()) {
            Util::warnOnException(function () use ($fields) {
                $iblockId = $fields['IBLOCK_ID'];
                $sharedKey = array_flip(ApplicationForm::iblockIds())[$iblockId];
                $listId = Email::nominationListIds()[$sharedKey];
                $userId = $fields['PROPERTY_VALUES']['USER'];
                Email::addUsersToList($listId, array(CUser::GetByID($userId)->GetNext()));
            });
        }
    }

    private static function onApplicationStatusChange($curr, $prev) {
        if (App::emailSubscriptionsEnabled()) {
            Util::warnOnException(function () use ($curr, $prev) {
                $currStatus = $curr['PROPERTIES']['STATUS']['VALUE_XML_ID'];
                $prevStatus = $prev['PROPERTIES']['STATUS']['VALUE_XML_ID'];
                if ($currStatus !== null) {
                    $userId = $curr['PROPERTIES']['USER']['VALUE'];
                    $user = CUser::GetByID($userId)->GetNext();
                    Email::addUsersToList(Email::statusListId($currStatus), array($user));
                } else {
                    $userId = $prev['PROPERTIES']['USER']['VALUE'];
                    Email::updateList(Email::statusListId($prevStatus), function ($userIds) use ($userId) {
                        return _::removeValue($userIds, $userId);
                    });
                }
                if ($prevStatus !== null) {
                    $userId = $prev['PROPERTIES']['USER']['VALUE'];
                    Email::updateList(Email::statusListId($prevStatus), function ($userIds) use ($userId) {
                        return _::removeValue($userIds, $userId);
                    });
                }
            });
        }
    }

    static function onAfterIBlockElementAdd($fields) {
        $iblockId = $fields['IBLOCK_ID'];
        if (ApplicationForm::isNomination($iblockId)) {
            self::onApplicationAdded($fields);
            ApplicationForm::syncGeneralInfo($fields['PROPERTY_VALUES']['USER']);
        } elseif (intval($iblockId) === Iblock::GENERAL_INFO) {
            ApplicationForm::syncGeneralInfo($fields['PROPERTY_VALUES']['USER']);
        }
    }

    static function onBeforeIBlockElementUpdate(&$fieldsRef) {
        if (ApplicationForm::isNomination($fieldsRef['IBLOCK_ID'])) {
            $fieldsRef['PREV'] = _::first(ib::collectElements(CIBlockElement::GetByID($fieldsRef['ID'])));
        }
    }

    static function onAfterIBlockElementUpdate($fields) {
        $status = function($element) {
            return $element['PROPERTIES']['STATUS']['VALUE_XML_ID'];
        };
        if (ApplicationForm::isNomination($fields['IBLOCK_ID'])) {
            $element = _::first(ib::collectElements(CIBlockElement::GetByID($fields['ID'])));
            $prev = $fields['PREV'];
            $statusChanged = $status($prev) !== $status($element);
            if ($statusChanged) {
                self::onApplicationStatusChange($element, $prev);
            }
            ApplicationForm::syncGeneralInfo($fields['PROPERTY_VALUES']['USER']);
        } elseif (intval($fields['IBLOCK_ID']) === Iblock::GENERAL_INFO) {
            ApplicationForm::syncGeneralInfo($fields['PROPERTY_VALUES']['USER']);
        }
    }

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

    static function onAfterSetUserGroup($userId) {
        $groupIds = array_map('intval', CUser::GetUserGroup($userId));
        $isNominee = in_array(User::NOMINEE_GROUP, $groupIds);
        if ($isNominee) {
            self::onNomineeAdded($userId);
        }
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
            // when expert is added by an admin, send a password reset link
            CUser::SendUserInfo($fields['ID'], App::SITE_ID, $msg, false, MailEvent::NEW_EXPERT);
        }
        return $fields;
    }

    static function onUserDelete($userId) {
        $iblockIds = array_merge(
            array_values(ApplicationForm::iblockIds()),
            array_values(Vote::iblockIds())
        );
        $iblockFilters = array_map(function($id) {
            return array('IBLOCK_ID' => $id);
        }, $iblockIds);
        $filter = array('PROPERTY_USER' => $userId, array_merge(array('LOGIC' => 'OR'), $iblockFilters));
        $result = (new CIBlockElement)->GetList(array(), $filter, false, false, array('ID'));
        $elementIds = _::mapValues(ib::collect($result), 'ID');
        $el = new CIBlockElement();
        $results = array_map(function($id) use ($el) {
            return $el->Update($id, array(
                'ACTIVE' => 'N'
            ));
        }, $elementIds);
        return $userId;
    }
}
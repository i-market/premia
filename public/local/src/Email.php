<?php
namespace App;

use CDsDeliveryList;
use CDsSubscriber;
use Core\Underscore as _;
use Core\Nullable as nil;

class Email {
    const NOMINEES = 1;
    const COMPLETED = 2;
    const PARTIALLY_FILLED = 3;
    const ACCEPTED = 4;
    const REJECTED = 5;
    /// nominations
    const SMALL_BUSINESS = 16;
    const BREAKTHROUGH = 17;
    const ETP = 18;
    const IMPORT_SUBSTITUTION = 19;
    const SALES_INNOVATION = 20;
    const EXPORTER = 21;
    const SALES = 22;
    const LAW = 23;


    static function nominationListIds() {
        return array(
            'SMALL_BUSINESS' => self::SMALL_BUSINESS,
            'BREAKTHROUGH' => self::BREAKTHROUGH,
            'ETP' => self::ETP,
            'IMPORT_SUBSTITUTION' => self::IMPORT_SUBSTITUTION,
            'SALES_INNOVATION' => self::SALES_INNOVATION,
            'EXPORTER' => self::EXPORTER,
            'SALES' => self::SALES,
            'LAW' => self::LAW
        );
    }

    static function statusListId($statusXmlId) {
        return array(
            'COMPLETED' => self::COMPLETED,
            'PARTIALLY_FILLED' => self::PARTIALLY_FILLED,
            'ACCEPTED' => self::ACCEPTED,
            'REJECTED' => self::REJECTED
        )[$statusXmlId];
    }

    private static function addSubscriber($user) {
        return (new CDsSubscriber)->add(array(
            'ACTIVE' => 'Y',
            'USER_ID' => $user['ID'],
            /// TODO on user update sync email, name
            'EMAIL' => $user['EMAIL'],
            'NAME' => $user['NAME']
        ));
    }

    static function ensureIsSubscriber($user) {
        $sub = new CDsSubscriber();
        if (!$sub->getByUserId($user['ID'])) {
            self::addSubscriber($user);
        }
        return $user;
    }

    static function updateList($listId, $userIdsFn) {
        $deliveryList = new CDsDeliveryList();
        $list = $deliveryList->getById($listId);
        if ($list === false) {
            trigger_error('trying to update a non-existent email list', E_USER_WARNING);
        }
        $existingIds = array_map('intval', nil::get(unserialize($list['FILTER'])[0]['filter'], array()));
        $userIds = $userIdsFn($existingIds);
        return $deliveryList->update($listId, array(
            'filter' => array(
                array(
                    'type_filter' => 'addByUserId',
                    'addById' => '',
                    'addByFilter' => array(
                        'ACTIVE' => '',
                        'IN_NAME' => '',
                        'IN_EMAIL' => '',
                    ),
                    'addByUserId' => join("\r\n", $userIds),
                    'addByUserFilter' => array(
                        'LOGIN' => '',
                        'NAME' => '',
                        'LAST_NAME' => '',
                        'SECOND_NAME' => '',
                        'PERSONAL_GENDER' => '',
                        'DATE_REGISTER_1' => '',
                        'DATE_REGISTER_2' => '',
                        'LAST_LOGIN_1' => '',
                        'LAST_LOGIN_2' => '',
                    )
                )
            )
        ));
    }

    static function addUsersToList($listId, $users) {
        foreach ($users as $user) {
            self::ensureIsSubscriber($user);
        }
        return self::updateList($listId, function($userIds) use ($users) {
            return array_merge($userIds, _::pluck($users, 'ID'));
        });
    }
}
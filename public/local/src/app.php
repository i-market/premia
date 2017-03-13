<?php

namespace App;

use Bitrix\Main\Config\Configuration;
use CEvent;
use COption;
use Core\Env;
use Core\Nullable as nil;
use Core\View as v;
use Core\Form as f;
use Core\Underscore as _;

class App {
    const SITE_ID = 's1';

    static function init() {
        EventHandlers::listen();
    }

    static function layoutContext() {
        global $USER;
        return array(
            'form_specs' => self::formSpecs(),
            'main_menu' => self::renderMainMenu(),
            'slider' => self::renderSlider(),
            'social_links' => v::renderIncludedArea('social_links.php'),
            'footer_left' => v::renderIncludedArea('footer_left.php'),
            'footer_copyright' => v::renderIncludedArea('footer_copyright.php'),
            'is_logged_in' => $USER->IsAuthorized(),
            'user_display_name' => $USER->GetFormattedName(),
            'profile_path' => User::profilePath(),
            'logout_link' => User::logoutLink()
        );
    }

    static function formSpecs() {
        $requiredMessage = "Пожалуйста, заполните это поле.";
        // TODO field specs are incomplete, don't use them to render forms
        $ret = _::keyBy(array(
            array(
                'name' => 'contact',
                'fields' => _::keyBy(array(
                    f::field('name', 'Ваше имя'),
                    f::field('email', 'Ваш E-mail'),
                    f::field('message', 'Ваше сообщение')
                ), 'name'),
                'validations' => array(
                    array(
                        'type' => 'required',
                        'fields' => array('name', 'email', 'message'),
                        'message' => $requiredMessage
                    )
                )
            ),
            array(
                'name' => 'signup',
                'fields' => _::keyBy(array(
                    f::field('full-name', 'ФИО'),
                    f::field('company', 'Название компании'),
                    f::field('email', 'E-mail'),
                    f::field('phone', 'Номер телефона'),
                    f::field('password', 'Пароль'),
                    f::field('password-confirmation', 'Введите пароль еще раз')
                ), 'name'),
                'validations' => array(
                    array(
                        'type' => 'required',
                        'fields' => array('full-name', 'company', 'email', 'phone', 'password', 'password-confirmation'),
                        'message' => $requiredMessage
                    ),
                    array(
                        'type' => 'email',
                        'fields' => array('email'),
                        'message' => 'Неверный адрес электронной почты.'
                    ),
                    // bitrix password length requirement
                    array(
                        'type' => 'minLength',
                        'minLength' => 6,
                        'fields' => array('password', 'password-confirmation'),
                        'message' => 'Пароль должен быть не менее {{ validation.minLength }} символов длиной.'
                    )
                )
            )
        ), 'name');
        return array_map(function($spec) {
            return _::set($spec, 'action', '/api/'.$spec['name']);
        }, $ret);
    }

    // TODO move to core?
    static function sendMailEvent($type, $siteId, $data) {
        if (\Core\App::env() === Env::DEV) {
            $event = array($type, $siteId, $data);
            return $event;
        } else {
            return (new CEvent)->Send($type, $siteId, $data);
        }
    }

    // TODO move to core?
    private static function config() {
        return nil::get(Configuration::getValue('app'), array());
    }

    static function mailFrom() {
        return _::get(self::config(), 'mail_from', COption::GetOptionString('main', 'email_from'));
    }

    static function mailTo() {
        return _::get(self::config(), 'mail_to', COption::GetOptionString('main', 'email_from'));
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

    static function renderSlider() {
        global $APPLICATION;
        ob_start();
        $APPLICATION->IncludeComponent(
        	"bitrix:news.list",
        	"slider",
        	Array(
        		"ACTIVE_DATE_FORMAT" => "j F Y",
        		"ADD_SECTIONS_CHAIN" => "N",
        		"AJAX_MODE" => "N",
        		"AJAX_OPTION_ADDITIONAL" => "",
        		"AJAX_OPTION_HISTORY" => "N",
        		"AJAX_OPTION_JUMP" => "N",
        		"AJAX_OPTION_STYLE" => "Y",
        		"CACHE_FILTER" => "N",
        		"CACHE_GROUPS" => "Y",
        		"CACHE_TIME" => "36000000",
        		"CACHE_TYPE" => "A",
        		"CHECK_DATES" => "Y",
        		"DETAIL_URL" => "",
        		"DISPLAY_BOTTOM_PAGER" => "Y",
        		"DISPLAY_DATE" => "Y",
        		"DISPLAY_NAME" => "Y",
        		"DISPLAY_PICTURE" => "Y",
        		"DISPLAY_PREVIEW_TEXT" => "Y",
        		"DISPLAY_TOP_PAGER" => "N",
        		"FIELD_CODE" => array("", ""),
        		"FILTER_NAME" => "",
        		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
        		"IBLOCK_ID" => Iblock::SLIDER_ID,
        		"IBLOCK_TYPE" => Iblock::CONTENT_TYPE,
        		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
        		"INCLUDE_SUBSECTIONS" => "Y",
        		"MESSAGE_404" => "",
        		"NEWS_COUNT" => PHP_INT_MAX,
        		"PAGER_BASE_LINK_ENABLE" => "N",
        		"PAGER_DESC_NUMBERING" => "N",
        		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
        		"PAGER_SHOW_ALL" => "N",
        		"PAGER_SHOW_ALWAYS" => "N",
        		"PAGER_TEMPLATE" => ".default",
        		"PAGER_TITLE" => '',
        		"PARENT_SECTION" => "",
        		"PARENT_SECTION_CODE" => "",
        		"PREVIEW_TRUNCATE_LEN" => "",
        		"PROPERTY_CODE" => array("LINK"),
        		"SET_BROWSER_TITLE" => "N",
        		"SET_LAST_MODIFIED" => "N",
        		"SET_META_DESCRIPTION" => "N",
        		"SET_META_KEYWORDS" => "N",
        		"SET_STATUS_404" => "N",
        		"SET_TITLE" => "N",
        		"SHOW_404" => "N",
                "SORT_BY1" => "ID",
                "SORT_BY2" => "SORT",
                "SORT_ORDER1" => "DESC",
                "SORT_ORDER2" => "ASC"
        	)
        );
        return ob_get_clean();
    }
}

class MailEvent {
    const CONTACT_FORM = 'CONTACT_FORM';
    const NEW_NOMINATION = 'NEW_NOMINATION';
}

class Iblock {
    const CONTENT_TYPE = 'content';
    const FORMS_TYPE = 'forms';
    const SLIDER_ID = 1;
    const PARTNERS_ID = 2;
    const NOMINATIONS_ID = 3;
    const GENERAL_INFO = 5;
    const SMALL_BUSINESS = 6;
    const BREAKTHROUGH = 7;
    const ETP = 8;
    const IMPORT_SUBSTITUTION = 9;
    const SALES_INNOVATION = 10;
    const EXPORTER = 11;
    const SALES = 12;
    const LAW = 13;
    const VOTE_SMALL_BUSINESS = 14;
}

class PageProperty {
    const LAYOUT = 'layout';
}

class User {
    const NOMINEE_GROUP = 5;
    const EXPERT_GROUP = 6;

    static function profilePath() {
        return v::path('auth/profile');
    }

    static function logoutLink() {
        return '?logout=yes';
    }

    static function parseFullName($fullName) {
        $full = trim($fullName);
        $parts = explode(' ', $full);
        if ($parts[0] === '') unset($parts[0]);
        if (count($parts) === 0) {
            return array();
        } elseif (count($parts) > 3) {
            return array('FULL_NAME' => $full);
        } else {
            $ret = array();
            $ret['FULL_NAME'] = $full;
            if (isset($parts[0])) $ret['LAST_NAME'] = $parts[0];
            if (isset($parts[1])) $ret['FIRST_NAME'] = $parts[1];
            if (isset($parts[2])) $ret['PATRONYMIC'] = $parts[2];
            return $ret;
        }
    }

    static function renderProfile($userGroups) {
        global $APPLICATION;
        $isExpert = in_array(self::EXPERT_GROUP, array_map('intval', $userGroups));
        ob_start();
        $APPLICATION->IncludeComponent(
            "bitrix:main.profile",
            $isExpert ? "expert" : "profile",
            Array(
                "AJAX_MODE" => "N",
                "AJAX_OPTION_ADDITIONAL" => "",
                "AJAX_OPTION_HISTORY" => "N",
                "AJAX_OPTION_JUMP" => "N",
                "AJAX_OPTION_STYLE" => "N",
                "CHECK_RIGHTS" => "N",
                "SEND_INFO" => "N",
                "SET_TITLE" => "N",
                "USER_PROPERTY" => array(),
                "USER_PROPERTY_NAME" => ""
            )
        );
        return ob_get_clean();
    }

    static function renderApplication($iblockId, $elementId) {
        // kind of important for security
        assert(in_array($iblockId, ApplicationForm::iblockIds()));
        global $APPLICATION;
        ob_start();
        $APPLICATION->IncludeComponent(
            "bitrix:news.detail",
            "application",
            Array(
                "ACTIVE_DATE_FORMAT" => "d.m.Y",
                "ADD_ELEMENT_CHAIN" => "Y",
                "ADD_SECTIONS_CHAIN" => "Y",
                "AJAX_MODE" => "N",
                "AJAX_OPTION_ADDITIONAL" => "",
                "AJAX_OPTION_HISTORY" => "N",
                "AJAX_OPTION_JUMP" => "N",
                "AJAX_OPTION_STYLE" => "N",
                "BROWSER_TITLE" => "-",
                "CACHE_GROUPS" => "Y",
                "CACHE_TIME" => "36000000",
                "CACHE_TYPE" => "A",
                "CHECK_DATES" => "Y",
                "DETAIL_URL" => "",
                "DISPLAY_BOTTOM_PAGER" => "N",
                "DISPLAY_DATE" => "N",
                "DISPLAY_NAME" => "N",
                "DISPLAY_PICTURE" => "N",
                "DISPLAY_PREVIEW_TEXT" => "N",
                "DISPLAY_TOP_PAGER" => "N",
                "ELEMENT_CODE" => "",
                "ELEMENT_ID" => $elementId,
                "FIELD_CODE" => array("",""),
                "IBLOCK_ID" => $iblockId,
                "IBLOCK_TYPE" => Iblock::FORMS_TYPE,
                "IBLOCK_URL" => "",
                "INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
                "MESSAGE_404" => "",
                "META_DESCRIPTION" => "-",
                "META_KEYWORDS" => "-",
                "PAGER_BASE_LINK_ENABLE" => "N",
                "PAGER_SHOW_ALL" => "N",
                "PAGER_TEMPLATE" => ".default",
                "PAGER_TITLE" => "Страница",
                "PROPERTY_CODE" => array("USER"),
                "SET_BROWSER_TITLE" => "N",
                "SET_CANONICAL_URL" => "N",
                "SET_LAST_MODIFIED" => "N",
                "SET_META_DESCRIPTION" => "Y",
                "SET_META_KEYWORDS" => "Y",
                "SET_STATUS_404" => "N",
                "SET_TITLE" => "N",
                "SHOW_404" => "N",
                "USE_PERMISSIONS" => "N",
                "USE_SHARE" => "N"
            )
        );
        return ob_get_clean();
    }
}

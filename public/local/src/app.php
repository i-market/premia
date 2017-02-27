<?php

namespace App;

use Bitrix\Main\Config\Configuration;
use CEvent;
use COption;
use Core\Env;
use Core\Nullable as nil;
use Core\View as v;
use Core\Form as f;
use CUser;
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
            'login_modal' => User::renderLoginForm(),
            'is_logged_in' => $USER->IsAuthorized(),
            'user_display_name' => User::getDisplayName($USER),
            'signup_path' => User::signupPath(),
            'profile_path' => User::profilePath(),
            'logout_link' => User::logoutLink()
        );
    }

    static function formSpecs() {
        $requiredMessage = "Пожалуйста, заполните поле «{{ label }}».";
        // TODO field specs are incomplete, don't use them for rendering
        $ret = _::keyBy(array(
            array(
                'name' => 'contact',
                'mail_subject' => 'Сообщение из формы «Напишите нам сообщение»',
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
}

class Iblock {
    const CONTENT_TYPE = 'content';
    const SLIDER_ID = 1;
    const PARTNERS_ID = 2;
    const NOMINATIONS_ID = 3;
}

class PageProperty {
    const LAYOUT = 'layout';
}

class User {
    /**
     * @param $user CUser
     * @return string
     */
    static function getDisplayName($user) {
        return $user->GetFormattedName();
    }

    static function profilePath() {
        return v::path('user/profile');
    }

    static function signupPath() {
        return v::path('user/signup');
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

    // TODO unused?
    static function renderLoginForm() {
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

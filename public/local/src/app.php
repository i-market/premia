<?php

namespace App;

use Core\View as v;
use CUser;

class App {
    static function layoutContext() {
        global $USER;
        return array(
            'main_menu' => self::renderMainMenu(),
            'slider' => self::renderSlider(),
            'social_links' => v::renderIncludedArea('social_links.php'),
            'footer_left' => v::renderIncludedArea('footer_left.php'),
            'footer_copyright' => v::renderIncludedArea('footer_copyright.php'),
            'auth_modal' => Auth::renderAuthForm(),
            'is_logged_in' => $USER->IsAuthorized(),
            'user_display_name' => Auth::getDisplayName($USER),
            'signup_path' => Auth::signupPath(),
            'profile_path' => Auth::profilePath(),
            'logout_link' => Auth::logoutLink()
        );
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
        		"SORT_BY1" => "ACTIVE_FROM",
        		"SORT_BY2" => "SORT",
        		"SORT_ORDER1" => "DESC",
        		"SORT_ORDER2" => "ASC"
        	)
        );
        return ob_get_clean();
    }
}

class Iblock {
    const CONTENT_TYPE = 'content';
    const SLIDER_ID = 1;
    const PARTNERS_ID = 2;
    const NOMINATIONS_ID = 3;
}

// TODO rename to User
class Auth {
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

    static function renderAuthForm() {
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

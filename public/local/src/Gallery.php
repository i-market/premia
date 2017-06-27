<?php

namespace App;

use Bitrix\Main\Loader;
use CIBlockElement;
use CIBlockSection;
use Core\Iblock as ib;
use Core\Nullable as nil;
use CFile;
use Core\Underscore as _;
use Core\View as v;

assert(Loader::includeModule('iblock'));

class Gallery {
    static function sections() {
        $result = CIBlockSection::GetList(array('SORT' => 'ASC'), array(
            'IBLOCK_ID' => Iblock::GALLERY_ID,
            'ACTIVE' => 'Y'
        ));
        $sections = array_map(function($section) {
            $filter = array('IBLOCK_ID' => Iblock::GALLERY_ID, 'SECTION_ID' => $section['ID'], 'ACTIVE' => 'Y');
            $limit = array('nTopCount' => 1);
            $truthy = (new CIBlockElement)->GetList(array('SORT' => 'ASC'), $filter, false, $limit)->GetNext();
            $elementMaybe = $truthy ? $truthy : null;
            $coverMaybe = nil::map($elementMaybe, function($element) {
                $resized = CFile::ResizeImageGet($element['PREVIEW_PICTURE'], array('width' => 500, 'height' => 500));
                return _::set($element, 'RESIZED', $resized);
            });
            return _::set($section, 'COVER_IMAGE', $coverMaybe);
        }, ib::collect($result));
        return array_filter($sections, function($section) {
            // filter out empty sections
            return $section['COVER_IMAGE'] !== null;
        });
    }

    static function renderGallerySlider($sectionId, $class) {
        global $APPLICATION;
        ob_start();
        $APPLICATION->IncludeComponent(
            "bitrix:news.list",
            "gallery_slider",
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
                "IBLOCK_ID" => Iblock::GALLERY_ID,
                "IBLOCK_TYPE" => Iblock::MEDIA_TYPE,
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
                "PARENT_SECTION" => $sectionId,
                "PARENT_SECTION_CODE" => "",
                "PREVIEW_TRUNCATE_LEN" => "",
                "PROPERTY_CODE" => array("", ""),
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
                "SORT_ORDER2" => "ASC",
                "CLASS" => $class
            )
        );
        return ob_get_clean();
    }

    static function renderGallery() {
        $sections = self::sections();
        $activeSectionId = _::first($sections)['ID'];
        $ctx = array(
            'slider' => self::renderGallerySlider($activeSectionId, 'active'),
            'sections' => array_map(function($section) use ($activeSectionId) {
                return _::set($section, 'ACTIVE', $section['ID'] === $activeSectionId);
            }, $sections)
        );
        return v::twig()->render(v::partial('gallery.twig'), $ctx);
    }
}

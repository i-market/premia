<?php

namespace App;

use Hendrix\View as v;
use Maximaster\Tools\Twig\TemplateEngine;
use Hendrix\Form as f;

class App {
    private static function placeholderOption($text, $value) {
        return array(
            'type' => 'placeholder',
            'text' => $text.'...',
            'value' => $value,
            'attributes' => array('disabled' => '', 'selected' => '')
        );
    }

    static function formSpecs() {
        $requiredMessage = "Пожалуйста, заполните поле «{{ label }}».";
        $ret = array(
            're_call' => array(
                'title' => 'Заказать звонок',
                'fields' => array(
                    f::field('name', 'ФИО'),
                    f::field('phone', 'Телефон'),
                    f::field('message', 'Сообщение', 'textarea')
                ),
                'validations' => array(
                    array(
                        'type' => 'required',
                        'fields' => array('name', 'phone'),
                        'message' => $requiredMessage
                    )
                )
            ),
            'write_letter' => array(
                'title' => 'Написать письмо',
                'fields' => array(
                    f::field('name', 'ФИО'),
                    f::field('email', 'Почта', 'email'),
                    f::field('message', 'Сообщение', 'textarea')
                ),
                'validations' => array(
                    array(
                        'type' => 'required',
                        'fields' => array('name', 'email', 'message'),
                        'message' => $requiredMessage
                    )
                )
            ),
            'rent' => array(
                'title' => 'Запросить форму на аренду',
                'fields' => array(
                    f::field('name', 'ФИО'),
                    f::field('phone', 'Телефон'),
                    f::field('email', 'Почта', 'email'),
                    f::select('type', array(
                        self::placeholderOption('Тип помещения', 'Не выбран'),
                        'Офис',
                        'Склад'
                    )),
                    f::field('space-needed', 'Интересующая площадь в м²')
                ),
                'validations' => array(
                    array(
                        'type' => 'required',
                        'fields' => array('name', 'phone', 'type'),
                        'message' => $requiredMessage
                    )
                )
            ),
            'order' => array(
                'title' => 'Заявка в торговый дом',
                'fields' => array(
                    f::field('name', 'ФИО'),
                    f::field('phone', 'Телефон'),
                    f::field('email', 'Почта', 'email'),
                    f::field('region', 'Регион'),
                    f::select('delivery_type', array(
                        self::placeholderOption('Форма получения', 'Не выбрана'),
                        'Самовывоз',
                        'Доставка'
                    )),
                    f::field('product_category', 'Вид продукции'),
                    f::select('product_volume', array(
                        self::placeholderOption('Объем', 'Не выбран'),
                        'Розница',
                        'Крупный опт',
                        'Мелкий опт'
                    )),
                    f::field('about_you', 'Коротко об организации')
                ),
                'validations' => array(
                    array(
                        'type' => 'required',
                        'fields' => array(
                            'name',
                            'phone',
                            'region',
                            'delivery_type',
                            'product_category',
                            'product_volume'
                        ),
                        'message' => $requiredMessage
                    )
                )
            ),
            'work_with_us' => array(
                'title' => 'Сотрудничество с нами',
                'fields' => array(
                    f::field('name', 'ФИО'),
                    f::field('phone', 'Телефон'),
                    f::field('email', 'Почта', 'email'),
                    f::select('type', array(
                        self::placeholderOption('Форма сотрудничества', 'Не выбрана'),
                        'Аренда',
                        'Покупка',
                        'Поставка'
                    )),
                    f::field('comment', 'Комментарий', 'textarea')
                ),
                'validations' => array(
                    array(
                        'type' => 'required',
                        'fields' => array('name', 'phone'),
                        'message' => $requiredMessage
                    )
                )
            ),
            'price_request' => array(
                'title' => 'Запросить цены',
                'fields' => array(
                    f::field('name', 'ФИО'),
                    f::field('phone', 'Телефон'),
                    f::field('email', 'Почта', 'email'),
                    f::field('region', 'Регион'),
                    f::field('comment', 'Комментарий', 'textarea')
                ),
                'validations' => array(
                    array(
                        'type' => 'required',
                        'fields' => array('name', 'phone'),
                        'message' => $requiredMessage
                    )
                )
            )
        );
        foreach ($ret as $name => $spec) {
            $ret[$name]['name'] = $name;
            $ret[$name]['action'] = '/api/'.$name;
        }
        return $ret;
    }
}

class Iblock {
    const PRODUCT_CATEGORIES_ID = 2;
    const NEWS_ID = 3;
}

class IblockType {
    const CONTENT = 'content';
}

class PageProperty {
    const LAYOUT = 'layout';
}

class View {
    static private $footer = null;

    static function showLayoutHeader($pageProperty, $defaultLayout, $context) {
        v::showForProperty($pageProperty, function($layout) use ($context) {
            $path = is_array($layout) ? $layout[0] : $layout;
            $propCtx = is_array($layout) ? $layout[1] : array();
            $twig = TemplateEngine::getInstance()->getEngine();
            $placeholder = '<page-placeholder/>';
            $ctx = array_merge(array('page' => $placeholder), $context, $propCtx);
            $html = $twig->render(SITE_TEMPLATE_PATH.'/layouts/'.$path, $ctx);
            list($header, $footer) = explode($placeholder, $html);
            self::$footer = $footer;
            echo $header;
        }, $defaultLayout);
    }

    static function showLayoutFooter() {
        global $APPLICATION;
        $APPLICATION->AddBufferContent(function() {
            assert(self::$footer !== null);
            return self::$footer;
        });
    }

    static function twig() {
        return TemplateEngine::getInstance()->getEngine();
    }
    
    static function geoUri($latLng) {
        return 'geo:'.$latLng['lat'].','.$latLng['lng'];
    }
}

// controller-ish stuff

class News {
    static function renderNewsItem($id) {
        global $APPLICATION;
        ob_start();
        $APPLICATION->IncludeComponent(
            "bitrix:news.detail",
            "news_section",
            Array(
                "ACTIVE_DATE_FORMAT" => "d.m.Y",
                "ADD_ELEMENT_CHAIN" => "N",
                "ADD_SECTIONS_CHAIN" => "N",
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
                "DISPLAY_DATE" => "Y",
                "DISPLAY_NAME" => "Y",
                "DISPLAY_PICTURE" => "Y",
                "DISPLAY_PREVIEW_TEXT" => "N",
                "DISPLAY_TOP_PAGER" => "N",
                "ELEMENT_CODE" => "",
                "ELEMENT_ID" => $id,
                "FIELD_CODE" => array("", ""),
                "IBLOCK_ID" => "3",
                "IBLOCK_TYPE" => "content",
                "IBLOCK_URL" => "",
                "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                "MESSAGE_404" => "",
                "META_DESCRIPTION" => "-",
                "META_KEYWORDS" => "-",
                "PAGER_BASE_LINK_ENABLE" => "N",
                "PAGER_SHOW_ALL" => "N",
                "PAGER_TEMPLATE" => ".default",
                "PAGER_TITLE" => "Страница",
                "PROPERTY_CODE" => array("IMAGES", ""),
                "SET_BROWSER_TITLE" => "N",
                "SET_CANONICAL_URL" => "N",
                "SET_LAST_MODIFIED" => "N",
                "SET_META_DESCRIPTION" => "N",
                "SET_META_KEYWORDS" => "N",
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
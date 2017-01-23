<?php

namespace App;

use Hendrix\View as v;
use Maximaster\Tools\Twig\TemplateEngine;
use Hendrix\Form as f;

class App {
    static function formSpecs() {
        $requiredMessage = "Пожалуйста, заполните поле «{{ label }}».";
        return array(
            're_call' => array(
                'name' => 're_call',
                'title' => 'Заказать звонок',
                'action' => '/todo', // TODO form action
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
                'name' => 'write_letter',
                'title' => 'Написать письмо',
                'action' => '/todo', // TODO form action
                'fields' => array(
                    array(
                        'name' => 'name',
                        'label' => 'ФИО'
                    ),
                    array(
                        'name' => 'email',
                        'type' => 'email',
                        'label' => 'Почта'
                    ),
                    array(
                        'name' => 'message',
                        'type' => 'textarea',
                        'label' => 'Сообщение'
                    )
                ),
                'validations' => array(
                    array(
                        'type' => 'required',
                        'fields' => array('name', 'email', 'message'),
                        'message' => $requiredMessage
                    )
                )
            )
        );
    }
}

class Iblock {
    const PRODUCT_CATEGORIES_ID = 2;
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
            $parts = explode($placeholder, $html);
            $header = $parts[0];
            $footer = $parts[1];
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
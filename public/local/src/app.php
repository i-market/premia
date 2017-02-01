<?php

namespace App;

use CEvent;
use Hendrix\Env;
use Hendrix\Null;
use Hendrix\View as v;
use Hendrix\Underscore as _;
use Maximaster\Tools\Twig\TemplateEngine;
use Hendrix\Form as f;
use Valitron\Validator;

class App {
    const SITE_ID = 's1';

    private static function placeholderOption($text, $value) {
        return array(
            'type' => 'placeholder',
            'text' => $text.'...',
            'value' => $value,
            'attributes' => array('selected' => '')
        );
    }

    // TODO move email_subject out of spec
    static function formSpecs() {
        $requiredMessage = "Пожалуйста, заполните поле «{{ label }}».";
        $ret = array(
            're_call' => array(
                'title' => 'Заказать звонок',
                'email_subject' => 'На сайте заказали обратный звонок',
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
                'email_subject' => 'С сайта отправлено письмо',
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
                'title' => 'Запрос на аренду',
                'email_subject' => 'На сайте запросили форму на аренду',
                'fields' => array(
                    f::field('name', 'ФИО'),
                    f::field('phone', 'Телефон'),
                    f::field('email', 'Почта', 'email'),
                    f::select('type', 'Тип помещения', array(
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
                'email_subject' => 'На сайте оставили заявку в торговый дом',
                'fields' => array(
                    f::field('name', 'ФИО'),
                    f::field('phone', 'Телефон'),
                    f::field('email', 'Почта', 'email'),
                    f::field('region', 'Регион'),
                    f::select('delivery_type', 'Форма получения', array(
                        self::placeholderOption('Форма получения', 'Не выбрана'),
                        'Самовывоз',
                        'Доставка'
                    )),
                    f::field('product_category', 'Вид продукции'),
                    f::select('product_volume', 'Объем', array(
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
                // TODO text
                'email_subject' => 'Сотрудничество с нами',
                'fields' => array(
                    f::field('name', 'ФИО'),
                    f::field('phone', 'Телефон'),
                    f::field('email', 'Почта', 'email'),
                    f::select('type', 'Форма сотрудничества', array(
                        self::placeholderOption('Форма сотрудничества', 'Не выбрана'),
                        'Аренда площадей',
                        'Покупка товаров',
                        'Поставка товаров'
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
                'email_subject' => 'На сайте запросили цены',
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

    static function emailEvent($params, $spec, $emailTo) {
        $lines = array_map(function($field) use ($params) {
            return $field['label'].': '.$params[$field['name']];
        }, $spec['fields']);
        return array(
            'SUBJECT' => $spec['email_subject'],
            'BODY' => join(PHP_EOL, $lines),
            // TODO config emails
            'EMAIL_FROM' => 'postmaster@domodedovo.nichost.ru',
            'EMAIL_TO' => $emailTo
        );
    }

    // TODO refactor
    static function formRoute($spec) {
        $handler = function($request, $response) use ($spec) {
            $mailConfig = array(
                array(
                    'forms' => array('re_call', 'write_letter', 'work_with_us'),
                    'email_to' => array('surovets@mspdom.ru', 'office@mspdom.ru', 'bezin@i-market.ru')
                ),
                array(
                    'forms' => array('order'),
                    'email_to' => array('surovets@mspdom.ru', 'trade@mspdom.ru', 'bezin@i-market.ru')
                ),
                array(
                    'forms' => array('rent'),
                    'email_to' => array('surovets@mspdom.ru', 'arenda@mspdom.ru', 'bezin@i-market.ru')
                )
            );
            $defaultEmailTo = array('surovets@mspdom.ru', 'bezin@i-market.ru');
            $fields = array_map(function($field) {
                return $field['name'];
            }, $spec['fields']);
            $params = $request->params($fields);
            $validator = new Validator($params);
            foreach ($spec['validations'] as $validation) {
                if ($validation['type'] === 'required') {
                    foreach ($validation['fields'] as $field) {
                        $fieldSpec = _::find($spec['fields'], function($fieldSpec) use ($field) {
                            return $fieldSpec['name'] === $field;
                        });
                        $tpl = View::twig()->createTemplate($validation['message']);
                        $message = $tpl->render($fieldSpec);
                        // mutate
                        $validator->rule('required', $field)->message($message);
                    }
                }
            }
            $validator->validate();
            $errors = $validator->errors();
            if (count($errors) === 0) {
                $cfgOrNull = _::find($mailConfig, function($item) use ($spec) {
                    return in_array($spec['name'], $item['forms']);
                });
                $emailTo = Null::get(Null::map($cfgOrNull, function($cfg) {
                    return $cfg['email_to'];
                }), $defaultEmailTo);
                foreach ($emailTo as $email) {
                    $event = self::emailEvent($params, $spec, $email);
                    self::sendMailEvent(MailEvent::CONTACT_FORMS, self::SITE_ID, $event);
                }
            }
            $errorsJson = array();
            foreach ($errors as $field => $messages) {
                // take the first message only
                $errorsJson[$field] = _::first($messages);
            }
            return $response->json(array('errors' => (object) $errorsJson));
        };
        return array(
            'method' => 'POST',
            'path' => $spec['action'],
            'handler' => $handler
        );
    }

    static function sendMailEvent($type, $siteId, $data) {
        if (\Hendrix\App::env() === Env::DEV) {
            $event = array($type, $siteId, $data);
            return $event;
        } else {
            return (new CEvent)->Send($type, $siteId, $data);
        }
    }

    static function youtubeVideoId($url) {
        $matchesRef = array();
        // https://github.com/mpratt/Embera/blob/master/Lib/Embera/Providers/Youtube.php#L30
        if (preg_match('~(?:v=|youtu\.be/|youtube\.com/embed/)([a-z0-9_-]+)~i', $url, $matchesRef)) {
            return $matchesRef[1];
        } else {
            return null;
        }
    }
}

class MailEvent {
    const CONTACT_FORMS = 'CONTACT_FORMS';
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
                "FIELD_CODE" => array("PREVIEW_PICTURE", "VIDEO_URL"),
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

class Rent {
    static function context() {
        $desc1 = '<strong>Арендуемая площадь —</strong> от 150 до 600 м²'
            .'<br>'.'<strong>Температурный режим —</strong> от +4 до +8 °С'
            .'<br>'.'<strong>Высота потолков —</strong> 10 м'
            .'<br>'.'<strong>Нагрузка на пол —</strong> 6 т/м²'
            .'<br>'.'<strong>Продукция —</strong> фрукты, овощи, ягоды, зелень, грибы, бахчевые, орехи, сухофрукты';
        $desc2 = '<strong>Арендуемая площадь —</strong> от 256 до 512 м²'
            .'<br>'.'<strong>Температурный режим —</strong> от −18 до +2 °С'
            .'<br>'.'<strong>Высота потолков —</strong> 6 м'
            .'<br>'.'<strong>Нагрузка на пол —</strong> 6 т/м²'
            .'<br>'.'<strong>Продукция —</strong> фрукты, овощи, мясо, птица, рыба и морепродукты, молочная продукция, бакалея';
        $abkDesc = '<strong>Общая площадь —</strong> 2 700 м²'
            .'<br>'.'<strong>Арендуемая площадь —</strong> от 22 м²'
            .'<br>'.'<strong>Этажность —</strong> 3/3'
            .'<br>'.'<strong>Планировка —</strong> коридорно-кабинетная'
            .'<br>'.'<strong>Кондиционирование —</strong> центральное'
            .'<br>'.'<strong>Отопление —</strong> центральное'
            .'<br>'.'<strong>Водоснабжение —</strong> горячая и холодная вода';
        return array(
            'scheme' => array(
                'korpus_2' => array(
                    'heading' => 'Корпус №2',
                    'image' => 'korpus-2.jpg',
                    'description' => $desc1
                ),
                'korpus_3' => array(
                    'heading' => 'Корпус №3',
                    'image' => 'korpus-3.jpg',
                    'description' => $desc1
                ),
                'korpus_4' => array(
                    'heading' => 'Корпус №4',
                    'image' => 'korpus-4.jpg',
                    'image_position' => 'top',
                    'description' => $desc1
                ),
                'korpus_11' => array(
                    'heading' => 'Корпус №11',
                    'image' => 'korpus-11.jpg',
                    'image_position' => 'top',
                    'description' => $desc2
                ),
                'korpus_12' => array(
                    'heading' => 'Корпус №12',
                    'image' => 'korpus-12.jpg',
                    'image_position' => 'top',
                    'description' => $desc2
                ),
                'korpus_13' => array(
                    'heading' => 'Корпус №13',
                    'image' => 'korpus-13.jpg',
                    'image_position' => 'top',
                    'description' => $desc2
                ),
                'abk' => array(
                    // TODO which image? desc?
                    'heading' => 'Административно-бытовой корпус',
                    'images' => array(
                        array('image' => 'abk.jpg', 'caption' => 'Первый этаж'),
                        array('image' => '2-floor-abk.jpg', 'caption' => 'Второй этаж')
                    ),
                    'image_position' => 'top',
                    'description' => $abkDesc
                )
            )
        );
    }
}
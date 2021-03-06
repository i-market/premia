<?php

namespace Core;

use Bitrix\Main\Config\Configuration;
use CBitrixComponentTemplate;
use CFile;
use CIBlock;
use CIBlockResult;
use Core\Underscore as _;
use Core\View as v;
use Core\Nullable as nil;
use Maximaster\Tools\Twig\TemplateEngine;
use Underscore\Methods\ArraysMethods;
use Underscore\Methods\StringsMethods;
use Valitron\Validator;

class Underscore extends ArraysMethods {
    static function mapValues($array, $f) {
        $ret = array();
        foreach ($array as $k => $v) {
            $ret[$k] = is_string($f) ? self::get($v, $f) : $f($v, $k);
        }
        return $ret;
    }

//    static function flat($array, $shallow = false) {
//        $ret = array();
//        foreach ($array as $item) {
//            if (is_array($item)) {
//                $ret = array_merge($ret, $shallow ? $item : self::flat($item));
//            } else {
//                $ret[] = $item;
//            };
//        }
//        return $ret;
//    }

    static function pick($array, $keys) {
        return array_filter($array, function ($key) use ($keys) {
            return in_array($key, $keys);
        }, ARRAY_FILTER_USE_KEY);
    }

    // TODO refactor: optimize?
    static function reduce($array, $f, $initial) {
        return array_reduce(array_keys($array), function($ret, $k) use ($array, $f) {
            return $f($ret, $array[$k], $k);
        }, $initial);
    }

    static function filter($array, $pred = null) {
        // restore indices
        return array_values(array_filter($array, $pred));
    }
    
    static function drop($array, $n) {
        return array_slice($array, $n);
    }

    static function take($array, $n) {
        return array_slice($array, 0, $n);
    }

    static function update($array, $key, $f) {
        $nullable = nil::map(self::get($array, $key), $f);
        return $nullable === null ? $array : self::set($array, $key, $nullable);
    }

    static function isEmpty($x) {
        return is_array($x) && count($x) === 0;
    }
    
    static function groupBy($array, $f) {
        $ret = array();
        foreach ($array as $x) {
            $key = is_string($f) ? self::get($x, $f) : $f($x);
            $ret[$key][] = $x;
        }
        return $ret;
    }

    static function uniqBy($array, $f) {
        $seen = array();
        return array_filter($array, function($x) use ($f, $seen) {
            $val = $f($x);
            if ($val === null) {
                return true;
            } elseif (_::contains($seen, $val)) {
                return false;
            } else {
                $seen[] = $val;
                return true;
            }
        });
    }

    // TODO function $by support
    static function keyBy($array, $by) {
        $ret = array();
        foreach ($array as $x) {
            $ret[$x[$by]] = $x;
        }
        return $ret;
    }

    static function identity($x) {
        return $x;
    }

    static function constantly($x) {
        return function() use ($x) {
            return $x;
        };
    }

    static function noop() {}
}

class Nullable {
    static public function get($nullable, $default) {
        return $nullable === null ? $default : $nullable;
    }

    static public function map($nullable, $f) {
        return $nullable !== null ? $f($nullable) : $nullable;
    }
}

class Strings extends StringsMethods {
    static function isEmpty($str) {
        return trim($str) === '';
    }

    static function ifEmpty($str, $value) {
        return self::isEmpty($str) ? $value : $str;
    }

    static function contains($s, $subString) {
        return strpos($s, $subString) !== false;
    }

    static function replaceAll($s, $pattern, $replacement) {
        while(preg_match($pattern, $s))
            $s = preg_replace($pattern, $replacement, $s);
        return $s;
    }
}

class Env {
    const DEV = "dev";
    const STAGE = "stage";
    const PROD = "prod";
}

class App {
    static function env() {
        $app = Nullable::get(Configuration::getValue('app'), array());
        return _::get($app, 'env', Env::PROD);
    }
}

class View {
    private static $assetManifest = null;
    private static $footer = null;

    static function asset($path) {
        $build = SITE_TEMPLATE_PATH.'/build';
        if (App::env() === Env::DEV) {
            return $build.'/assets/'.$path;
        } else {
            if (self::$assetManifest === null) {
                $manifestFile = $_SERVER['DOCUMENT_ROOT'].$build.'/rev/rev-manifest.json';
                self::$assetManifest = json_decode(file_get_contents($manifestFile), true);
            }
            if (isset(self::$assetManifest[$path])) {
                return $build.'/rev/'.self::$assetManifest[$path];
            } else {
                return $build.'/assets/'.$path;
            }
        }
    }

    static function showForProperty($propName, $f, $defaultVal = null) {
        global $APPLICATION;
        $APPLICATION->AddBufferContent(function() use ($propName, $defaultVal, $f, &$APPLICATION) {
            $propVal = $APPLICATION->GetProperty($propName, $defaultVal);
            ob_start();
            if ($propVal !== false) {
                $f($propVal);
            }
            return ob_get_clean();
        });
    }

    static function showLayoutHeader($pageProperty, $defaultLayout, $context) {
        v::showForProperty($pageProperty, function($layout) use ($context) {
            if (is_callable($context)) {
                $context = $context();
            }
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

    static function partial($path) {
        return SITE_TEMPLATE_PATH.'/partials/'.$path;
    }

    static function path($path) {
        // TODO ad-hoc
        if ($path === '/') return SITE_DIR;
        return SITE_DIR.$path.'/';
    }

    static function includedArea($path) {
        return SITE_DIR.'include/'.$path;
    }

    static function layout($path) {
        return SITE_TEMPLATE_PATH.'/layouts/'.$path;
    }

    static function renderIncludedArea($path, $options = array()) {
        global $APPLICATION;
        $opts = array_merge(array(
            'TEMPLATE' => '.default',
            'PARAMS' => array()
        ), $options);
        ob_start();
        $APPLICATION->IncludeComponent(
            "bitrix:main.include",
            $opts['TEMPLATE'],
            Array(
                "AREA_FILE_SHOW" => "file",
                "PATH" => v::includedArea($path)
            ),
            null,
            $opts['PARAMS']
        );
        return ob_get_clean();
    }

    static function assocResized($items, $key, $dimensions) {
        return array_map(function($item) use ($key, $dimensions) {
            $resized = CFile::ResizeImageGet($item[$key], $dimensions);
            return _::set($item, $key.'.RESIZED', $resized);
        }, $items);
    }
}

class Form {
    static function field($name, $label, $type = 'text') {
        return array(
            'name' => $name,
            'type' => $type,
            'label' => $label
        );
    }

    // TODO label?
    static function select($name, $label, $options) {
        return array(
            'name' => $name,
            'label' => $label,
            'type' => 'select',
            'options' => array_map(function($option) {
                return is_array($option) ? $option : array(
                    'text' => $option,
                    'value' => $option,
                    'attributes' => array()
                );
            }, $options)
        );
    }

    static function formRoute($spec, $f) {
        $rule = function($validation) {
            if ($validation['type'] === 'minLength') {
                return array('lengthMin', $validation['minLength']);
            } else {
                return array($validation['type']);
            }
        };
        $handler = function($request, $response) use ($spec, $f, $rule) {
            // TODO refactor implicit global fields
            $globalFields = ['g-recaptcha-response'];
            $params = $request->params(array_merge(_::pluck($spec['fields'], 'name'), $globalFields));
            $validator = new Validator($params);
            foreach ($spec['validations'] as $validation) {
                foreach ($validation['fields'] as $field) {
                    $tpl = View::twig()->createTemplate($validation['message']);
                    $message = $tpl->render(array(
                        'field' => $spec['fields'][$field],
                        'validation' => $validation
                    ));
                    $r = $rule($validation);
                    $type = _::first($r);
                    $args = _::drop($r, 1);
                    $ruleArgs = array_merge(array($type, $field), $args);
                    // mutate
                    call_user_func_array(array($validator, 'rule'), $ruleArgs)->message($message);
                }
            }
            $validator->validate();
            $errors = $validator->errors();
            if ($params['g-recaptcha-response'] !== null) {
                $captchaMessage = 'Капча не подошла. Пожалуйста, попробуйте еще раз.';
                // mutate
                $errors = array_merge($errors, \App\App::validateRecaptcha($params['g-recaptcha-response'], $captchaMessage));
            }
            return $f($params, $errors, $response);
        };
        return array(
            'method' => 'POST',
            'path' => $spec['action'],
            'handler' => $handler
        );
    }
}

class NewsListLike {
    /**
     * @param array $element
     * @param CBitrixComponentTemplate $template
     * @return string dom element id
     */
    static function addEditingActions($element, $template) {
        assert(array_key_exists('EDIT_LINK', $element));
        assert(array_key_exists('DELETE_LINK', $element));
        $template->AddEditAction($element['ID'], $element['EDIT_LINK'],
            CIBlock::GetArrayByID($element['IBLOCK_ID'], 'ELEMENT_EDIT'));
        $template->AddDeleteAction($element['ID'], $element['DELETE_LINK'],
            CIBlock::GetArrayByID($element['IBLOCK_ID'], 'ELEMENT_DELETE'),
            array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        return $template->GetEditAreaId($element['ID']);
    }
}

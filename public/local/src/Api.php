<?php

namespace App;

use App\ApplicationForm;
use Core\Strings as str;
use Core\Underscore as _;
use CUser;

class Api {
    static function formResponse($errors, $bxMessage) {
        return array(
            'errors' => (object) $errors,
            'bxMessage' => (object) array_change_key_case($bxMessage, CASE_LOWER)
        );
    }

    static function reduceFormResults($results) {
        return array_reduce($results, function($ret, $result) {
            if ($ret === null) return $result;
            $msgs = _::clean(array($ret['errorMessageMaybe'], $result['errorMessageMaybe']));
            return $ret['isSuccess'] && $result['isSuccess']
                ? $ret
                : array(
                    'isSuccess' => false,
                    'errorMessageMaybe' => _::isEmpty($msgs) ? null : join('', $msgs)
                );
        });
    }

    private static function applicationFields($user, $params) {
        global $USER;
        $elementName = ApplicationForm::genericElementName($user, $USER->GetFormattedName());
        $fields = array();
        foreach(ApplicationForm::iblockIds() as $key => $iblockId) {
            // TODO refactor missing iblocks
            if (_::has($params, str::lower($key))) {
                $paramsPropsPath = str::lower($key) . '.properties';
                $propValues = array_change_key_case(_::get($params, $paramsPropsPath, array()), CASE_UPPER);
                $propTxtValues = array_map(function($value) {
                    return array(
                        'VALUE' => array(
                            'TYPE' => 'text',
                            'TEXT' => $value
                        )
                    );
                }, $propValues);
                $fields[$key] = array(
                    'IBLOCK_ID' => $iblockId,
                    'NAME' => $elementName,
                    'PROPERTY_VALUES' => array_merge($propTxtValues, array(
                        'USER' => $USER->GetID()
                    ))
                );
            }
        }
        return $fields;
    }

    // TODO refactor
    private static function files($files) {
        $ret = array();
        for($i = 0; $i < count($files['name']['file']); $i++) {
            $ret[] = array(
                'name' => $files['name']['file'][$i],
                'type' => $files['type']['file'][$i],
                'tmp_name' => $files['tmp_name']['file'][$i],
                'error' => $files['error']['file'][$i],
                'size' => $files['size']['file'][$i]
            );
        }
        return $ret;
    }

    static function handleApplication($request) {
        global $USER;
        $user = CUser::GetByID($USER->GetID())->GetNext();
        // TODO sanitize params
        $params = array_filter($request->params(), function($group) {
            return boolval($group['is_dirty']);
        });
        $fields = self::applicationFields($user, $params);
        $files = array_map(function($fs) {
            return self::files($fs);
        }, $_FILES);
        foreach($files as $key => $files) {
            $propValue = array_map(function($file) {
                return array('VALUE' => $file);
            }, $files);
            $fields[str::upper($key)]['PROPERTY_VALUES']['FILES'] = $propValue;
        }
        // TODO better noop? info warn
        if (_::isEmpty($fields)) {
            return array('isSuccess' => true, 'errorMessageMaybe' => null);
        } else {
            // TODO if request method == POST update, otherwise delete
            return ApplicationForm::updateApplication($USER->GetID(), $fields);
        }
    }
}
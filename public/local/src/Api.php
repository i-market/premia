<?php

namespace App;

use App\ApplicationForm;
use CFile;
use CIBlockElement;
use Core\Strings as str;
use Core\Underscore as _;
use CUser;
use Core\Iblock as ib;

class Api {
    static function formResponse($errors, $bxMessage) {
        return array(
            'errors' => (object) $errors,
            'bxMessage' => (object) array_change_key_case($bxMessage, CASE_LOWER)
        );
    }

    // an attempt to refactor the api mess
    /**
     * @param CIBlockElement $element
     */
    static function iblockOpResponse($element, $result) {
        $isSuccess = $result === true || is_int($result);
        return $isSuccess
            ? array(
                'type' => 'success',
                // same as in js
                'message' => 'Ваши изменения были сохранены.'
            )
            : array(
                'type' => 'error',
                'message' => $element->LAST_ERROR
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
            $paramsKey = str::lower($key);
            // TODO refactor missing iblocks
            if (_::has($params, $paramsKey)) {
                $paramsPropsPath = $paramsKey . '.properties';
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
                        'USER' => $USER->GetID(),
                        'AWARD' => App::getActiveAward()
                    ))
                );
                $group = $params[$paramsKey];
                $filesProp = array_map(function($file) {
                    return array('VALUE' => $file);
                }, _::get($group, 'files', array()));
                $deleteFileValuesFn = function($elementId) use ($group, $iblockId) {
                    $propCode = 'FILES';
                    $props = ib::collect(CIBlockElement::GetProperty($iblockId, $elementId, 'sort', 'asc', array('CODE' => $propCode)));
                    return array_reduce(
                        $props,
                        function($values, $prop) use ($elementId, $iblockId, $group) {
                            return in_array($prop['VALUE'], $group['delete_files'])
                                ? _::set($values, $prop['PROPERTY_VALUE_ID'], array('VALUE' => array('del' => 'Y')))
                                : $values;
                        },
                        array()
                    );
                };
                $fields[$key]['PROPERTY_VALUES']['FILES'] = $filesProp;
                // TODO refactor?
                $fields[$key]['DELETE_FILE_VALUES_FN'] = $deleteFileValuesFn;
            }
        }
        return $fields;
    }

    // TODO refactor
    private static function files($files) {
        $key = 'file';
        $ret = array();
        for($i = 0; $i < count($files['name'][$key]); $i++) {
            $ret[] = array(
                'name' => $files['name'][$key][$i],
                'type' => $files['type'][$key][$i],
                'tmp_name' => $files['tmp_name'][$key][$i],
                'error' => $files['error'][$key][$i],
                'size' => $files['size'][$key][$i]
            );
        }
        return $ret;
    }

    static function handleApplication($request) {
        global $USER;
        if (App::state()['APPLICATIONS_LOCKED']) {
            trigger_error('someone tried to change a locked application', E_USER_WARNING);
            return array(
                'isSuccess' => false,
                'errorMessageMaybe' => Messages::APPLICATIONS_LOCKED
            );
        } else {
            $user = CUser::GetByID($USER->GetID())->GetNext();
            $files = _::mapValues($_FILES, function ($fs) {
                return self::files($fs);
            });
            // TODO sanitize params
            $params = $request->params();
            $filteredParams = array();
            foreach ($params as $key => $group) {
                if (!is_array($group)) continue;
                $isEmpty = _::matches($group['properties'], function ($value) {
                    return str::isEmpty($value);
                });
                // user touched the form?
                $isDirty = boolval($group['is_dirty']);
                $hasFiles = array_key_exists($key, $files);
                $hasFileOps = $hasFiles || array_key_exists('delete_files', $group);
                if ($hasFiles) {
                    $group['files'] = $files[$key];
                }
                // decide which field groups to act upon
                if ($hasFileOps || ($isDirty && !$isEmpty)) {
                    $filteredParams[$key] = $group;
                }
            };
            $fields = self::applicationFields($user, $filteredParams);
            if (_::isEmpty($fields)) {
                // TODO refactor: response types
                return array(
                    'isSuccess' => true,
                    'errorMessageMaybe' => null,
                    'type' => 'info',
                    'message' => 'Нет изменений, требующих сохранения.'
                );
            } else {
                return ApplicationForm::updateApplication($USER->GetID(), $fields);
            }
        }
    }

    static function downloadFile($fileId) {
        $file = CFile::GetFileArray($fileId);
        CFile::ViewByUser($file, array('attachment_name' => $file['ORIGINAL_NAME']));
    }
}
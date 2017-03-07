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
            $paramsPropsPath = str::lower($key).'.properties';
            $propValues = array_change_key_case(_::get($params, $paramsPropsPath, array()), CASE_UPPER);
            $fields[$key] = array(
                'IBLOCK_ID' => $iblockId,
                'NAME' => $elementName,
                'PROPERTY_USER' => $USER->GetID(),
                'PROPERTY_VALUES' => array_merge($propValues, array(
                    'USER' => $USER->GetID()
                ))
            );
        }
        return $fields;
    }

    static function handleApplication($request) {
        global $USER;
        $user = CUser::GetByID($USER->GetID())->GetNext();
        // TODO sanitize params
        $params = $request->params();
        $fields = self::applicationFields($user, $params);
        // TODO if request method == POST update, otherwise delete
        return ApplicationForm::updateApplication($USER->GetID(), $fields);
    }
}
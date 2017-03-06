<?php

namespace App;

use App\ApplicationForm;
use Core\Strings as str;
use CUser;

class Api {
    static function formResponse($errors, $bxMessage) {
        return array(
            'errors' => (object) $errors,
            'bxMessage' => (object) array_change_key_case($bxMessage, CASE_LOWER)
        );
    }

    static function groupProperties($fields) {
        $prefix = 'PROPERTY_';
        foreach ($fields as $k => $v) {
            if (str::startsWith($k, $prefix)) {
                $withoutPrefix = mb_substr($k, mb_strlen($prefix));
                $fields['PROPERTY_VALUES'][$withoutPrefix] = $v;
            }
        }
        return $fields;
    }

    static function handleApplication($request) {
        global $USER;
        $user = CUser::GetByID($USER->GetID())->GetNext();
        // TODO sanitize params
        $params = $request->params();
        $elementName = ApplicationForm::genericElementName($user, $USER->GetFormattedName());
        $fields = self::groupProperties(array_merge($params, array(
            'IBLOCK_ID' => Iblock::GENERAL_INFO,
            'NAME' => $elementName,
            'PROPERTY_USER' => $USER->GetID()
        )));
        // TODO if request method == POST update, otherwise delete
        $result = ApplicationForm::updateGeneralInfo($USER->GetID(), $fields);
        $isSuccess = $result === true || is_int($result);
        return array('isSuccess' => $isSuccess);
    }
}
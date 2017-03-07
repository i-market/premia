<?php

use App\ApplicationForm;
use Core\Underscore as _;

$application = ApplicationForm::application($USER->GetID());
//$arResult['APPLICATION'] = _::update($application, 'PROPERTIES', function($properties) {
//    return _::mapValues($properties, function($prop, $key) {
//        return _::has($prop, 'VALUE.TEXT')
//            ? _::set($prop, 'TEXTAREA');
//    });
//});
$arResult['APPLICATION'] = $application;

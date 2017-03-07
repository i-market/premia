<?php

use App\ApplicationForm;
use Core\Underscore as _;
use Core\Strings as str;
use Core\Iblock as ib;

// TODO move to `ApplicationForm`
$iblockIds = ApplicationForm::iblockIds();
$application = ApplicationForm::application($USER->GetID());
// mutate
$application = array_map(function($element) {
    return _::update($element, 'PROPERTIES.FILES.VALUE', function($fileIds) {
        return array_map(function($fileId) {
            return CFIle::GetFileArray($fileId);
        }, $fileIds);
    });
}, $application);
$excludeProps = array('USER', 'FILES');
$inputs = _::mapValues($application, function($el, $iblockKey) use ($iblockIds, $excludeProps) {
    if ($el === null) {
        $props = ib::collect(CIBlock::GetProperties($iblockIds[$iblockKey]));
        $inputProps = array_filter($props, function($prop) use ($excludeProps) {
            return !in_array($prop['CODE'], $excludeProps);
        });
        return array_map(function($prop) use ($iblockKey) {
            return array(
                'LABEL' => $prop['NAME'],
                'VALUE' => null,
                'NAME' => str::lower($iblockKey) . '[properties][' . str::lower($prop['CODE']) . ']'
            );
        }, $inputProps);
    } else {
        // TODO DRY
        $inputProps = array_filter($el['PROPERTIES'], function($prop) use ($excludeProps) {
            return !in_array($prop['CODE'], $excludeProps);
        });
        return _::mapValues($inputProps, function ($prop, $propKey) use ($iblockKey) {
            // TODO refactor: DRY
            return array(
                'LABEL' => $prop['NAME'],
                'VALUE' => $prop['VALUE']['TEXT'],
                'NAME' => str::lower($iblockKey) . '[properties][' . str::lower($propKey) . ']'
            );
        });
    }
});
$arResult['APPLICATION'] = _::set($application, 'INPUTS', $inputs);

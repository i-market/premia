<?php

use App\ApplicationForm;
use App\User;
use Core\Underscore as _;

$iblockId = intval($arResult['IBLOCK']['ID']);
$arResult['DISPLAY_NAME'] = ApplicationForm::getDisplayName($arResult);
$arResult['TEXT_PROPERTIES'] = array_filter($arResult['PROPERTIES'], function($prop) use ($iblockId) {
    return ApplicationForm::isPublicProperty($iblockId, $prop['CODE']);
});
$fileIds = _::get($arResult, 'PROPERTIES.FILES.VALUE', array());
$arResult['FILES'] = array_map(function($fileId) {
    return CFile::GetFileArray($fileId);
}, $fileIds);
$arResult['BACK_LINK'] = User::profilePath();
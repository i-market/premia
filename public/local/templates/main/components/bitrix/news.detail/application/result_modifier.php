<?php

use App\ApplicationForm as af;
use App\User;
use App\Vote;
use Core\Underscore as _;
use Core\Iblock as ib;

$iblockId = intval($arResult['IBLOCK']['ID']);
$arResult['DISPLAY_NAME'] = af::getDisplayName($arResult);
$arResult['TEXT_PROPERTIES'] = array_filter($arResult['PROPERTIES'], function($prop) use ($iblockId) {
    return af::isPublicProperty($iblockId, $prop['CODE']);
});
$fileIds = _::get($arResult, 'PROPERTIES.FILES.VALUE', array());
$arResult['FILES'] = array_map(function($fileId) {
    return CFile::GetFileArray($fileId);
}, $fileIds);
// application and vote keys should match
$voteIblockId = af::voteIblockId($iblockId);
$voteValuelessProps = _::keyBy(ib::collect(CIBlock::GetProperties($voteIblockId)), 'CODE');
// TODO move vote stuff to component epilogue
$userVoteMaybe = Vote::getByUser($USER->GetID(), $voteIblockId, array('PROPERTY_FORM' => $arResult['ID']));
$voteProps = _::get($userVoteMaybe, 'PROPERTIES', $voteValuelessProps);
$publicVoteProps = array_filter($voteProps, function($prop) use ($voteIblockId) {
    return Vote::isPublicProperty($voteIblockId, $prop['CODE']);
});
$arResult['BACK_LINK'] = User::profilePath();
$arResult['VOTE'] = array(
    'ID_MAYBE' => $userVoteMaybe['ID'],
    'PROPERTIES' => $publicVoteProps
);
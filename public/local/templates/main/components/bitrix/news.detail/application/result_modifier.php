<?php

use App\ApplicationForm as af;
use App\User;
use App\Vote;
use Core\Underscore as _;
use Core\Iblock as ib;

$iblockId = intval($arResult['IBLOCK']['ID']);
$iblockKey = array_flip(af::iblockIds())[$iblockId];
$arResult['DISPLAY_NAME'] = af::getDisplayName($arResult);
$arResult['TEXT_PROPERTIES'] = array_filter($arResult['PROPERTIES'], function($prop) use ($iblockId) {
    return af::isPublicProperty($iblockId, $prop['CODE']);
});
$fileIds = _::get($arResult, 'PROPERTIES.FILES.VALUE', array());
$arResult['FILES'] = array_map(function($fileId) {
    return CFile::GetFileArray($fileId);
}, $fileIds);
// application and vote keys should match
$voteIblockId = Vote::iblockIds()[$iblockKey];
$voteValuelessProps = _::keyBy(ib::collect(CIBlock::GetProperties($voteIblockId)), 'CODE');
// TODO move vote stuff to component epilogue
$el = new CIBlockElement();
$filter = array(
    'IBLOCK_ID' => $voteIblockId,
    'ACTIVE' => 'Y',
    'PROPERTY_USER' => $USER->GetID(),
    'PROPERTY_FORM' => $arResult['ID']
);
$userVote = _::first(ib::collectElements($el->GetList(array('SORT' => 'ASC'), $filter)));
$voteProps = _::get($userVote, 'PROPERTIES', $voteValuelessProps);
$publicVoteProps = array_filter($voteProps, function($prop) use ($voteIblockId) {
    return Vote::isPublicProperty($voteIblockId, $prop['CODE']);
});
$arResult['BACK_LINK'] = User::profilePath();
$arResult['VOTE'] = array(
    'PROPERTIES' => $publicVoteProps
);
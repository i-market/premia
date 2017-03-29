<?php

use App\App;
use App\ApplicationForm as af;
use App\ApplicationForm;
use App\User;
use App\Vote;
use App\Iblock;
use Core\Underscore as _;
use Core\Iblock as ib;

// mutate title
$APPLICATION->SetTitle(af::getDisplayName($arResult));
$iblockId = intval($arResult['IBLOCK']['ID']);
$arResult['DISPLAY_NAME'] = af::getDisplayName($arResult);
$appPublicProps = array_filter($arResult['PROPERTIES'], function($prop) use ($iblockId) {
    return af::isPublicProperty($iblockId, $prop['CODE']);
});
$appUser = $arResult['PROPERTIES']['USER']['VALUE'];
$filter = array_merge(array('IBLOCK_ID' => Iblock::GENERAL_INFO, 'PROPERTY_USER' => $appUser), ApplicationForm::activeFilter());
$generalInfo = _::first(ib::collectElements((new CIBlockElement())->GetList(array(), $filter)));
$generalInfoPublicProps = array_filter($generalInfo['PROPERTIES'], function($prop) use ($generalInfo) {
    return af::isPublicProperty($generalInfo['IBLOCK_ID'], $prop['CODE']);
});
$arResult['TEXT_PROPERTIES'] = array(
    'GENERAL_INFO' => $generalInfoPublicProps,
    'APPLICATION' => $appPublicProps
);
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
$arResult['HEADING'] =
    af::isPersonalNomination($iblockId)
        ? 'Анкеты в персональной номинации'
        : 'Анкеты компаний поставщиков';
$arResult['APP_STATE'] = App::state();
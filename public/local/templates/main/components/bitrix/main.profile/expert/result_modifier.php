<?php

use App\ApplicationForm;
use App\Vote;
use Core\Underscore as _;
use Core\Iblock as ib;

$el = new CIBlockElement();
$applications = _::mapValues(ApplicationForm::iblockIds(), function($iblockId) use ($el) {
    $filter = array('IBLOCK_ID' => $iblockId, 'ACTIVE' => 'Y');
    $apps = ib::collectElements($el->GetList(array('SORT' => 'ASC'), $filter));
    return array_map(function($app) use ($iblockId) {
        $appUserId = $app['PROPERTIES']['USER']['VALUE'];
        $ret = _::set($app, 'DISPLAY_NAME', ApplicationForm::getDisplayName($app));
        return _::set($ret, 'VOTE_PATH', Vote::votePath($iblockId, $app['ID']));
    }, $apps);
});
$userVotes = _::mapValues(Vote::iblockIds(), function($iblockId) use ($el) {
    global $USER;
    $filter = array('IBLOCK_ID' => $iblockId, 'ACTIVE' => 'Y', 'PROPERTY_USER' => $USER->GetID());
    $votes = ib::collectElements($el->GetList(array('SORT' => 'ASC'), $filter));
    return array_map(function($vote) use ($iblockId) {
        $scores = array_filter($vote['PROPERTIES'], function($prop) use ($iblockId) {
            return Vote::isPublicProperty($iblockId, $prop['CODE']);
        });
        return _::set($vote, 'SCORES', $scores);
    }, $votes);
});
$nominations = array_reduce(
    array_keys($applications),
    function($nominations, $ibKey) use ($applications, $userVotes) {
        $nominations[$ibKey] = array_map(function($app) use ($userVotes, $ibKey) {
            return array(
                'APPLICATION' => $app,
                'VOTE' => _::find($userVotes[$ibKey], function($vote) use ($app) {
                    return _::get($vote, 'PROPERTIES.FORM.VALUE') === $app['ID'];
                })
            );
        }, $applications[$ibKey]);
        return $nominations;
    },
    array()
);
$arResult['NOMINATIONS'] = $nominations;

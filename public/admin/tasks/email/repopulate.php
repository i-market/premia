<?php
use App\ApplicationForm;
use App\Email;
use App\User;
use Core\Iblock as ib;
use Core\Underscore as _;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Раздел администратора");

// TODO refactor with `User` functions
if (User::ensureUserIsAdmin()) {
    $el = new CIBlockElement();
    /// all nominees
    // TODO clean up
    /** @noinspection PhpPassByRefInspection */
    $nominees = ib::collect(CUser::GetList(($by = "NAME"), ($order = "asc"), Array("GROUPS_ID"=>Array(User::NOMINEE_GROUP), "ACTIVE"=>"Y"), Array("FIELDS"=>Array("ID", "EMAIL", "NAME"))));
    foreach ($nominees as $user) {
        Email::ensureIsSubscriber($user);
    }
    $results[] = Email::updateList(Email::NOMINEES, _::constantly(_::pluck($nominees, 'ID')));
    $nominationIds = array_filter(ApplicationForm::iblockIds(), function($iblockId) {
        return ApplicationForm::isNomination($iblockId);
    });
    $applications = array_reduce($nominationIds, function($acc, $iblockId) use ($el) {
        $filter = array('IBLOCK_ID' => $iblockId, 'ACTIVE' => 'Y');
        $elements = ib::collectElements($el->GetList(array('SORT' => 'ASC'), $filter));
        return array_merge($acc, $elements);
    }, array());
    /// by status
    // TODO filter out empty key
    $appGroups = _::groupBy($applications, function($element) {
        return $element['PROPERTIES']['STATUS']['VALUE_XML_ID'];
    });
    foreach ($appGroups as $status => $items) {
        $userIds = array_map(function($item) {
            return $item['PROPERTIES']['USER']['VALUE'];
        }, $items);
        $listId = Email::statusListId($status);
        if ($listId !== null) {
            $results[] = Email::updateList($listId, _::constantly($userIds));
        }
    }
    /// by nomination
    $byNomination = _::groupBy($applications, function($element) {
        $sharedKey = array_flip(ApplicationForm::iblockIds())[$element['IBLOCK_ID']];
        return $sharedKey;
    });
    foreach ($byNomination as $key => $items) {
        $userIds = array_map(function($item) {
            return $item['PROPERTIES']['USER']['VALUE'];
        }, $items);
        $listId = Email::nominationListIds()[$key];
        if ($listId !== null) {
            $results[] = Email::updateList($listId, _::constantly($userIds));
        }
    }
    var_dump($results);
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
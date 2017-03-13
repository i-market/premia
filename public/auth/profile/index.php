<?
use App\PageProperty;
use App\User;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Личный кабинет");
$APPLICATION->SetPageProperty(PageProperty::LAYOUT, 'bare.twig');
?>

<?= User::renderProfile(CUser::GetUserGroup($USER->GetID())) ?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
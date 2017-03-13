<?
use App\PageProperty;
use App\User;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty(PageProperty::LAYOUT, 'bare.twig');
?>

<?= User::renderApplication($_REQUEST['IBLOCK_ID'], $_REQUEST['ELEMENT_ID']) ?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
<?
use App\Admin;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Раздел администратора");
?>

<?= Admin::render($_REQUEST, true) ?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
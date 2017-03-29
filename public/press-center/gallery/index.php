<?
use App\Gallery;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Фотогалерея");
?>

<?= Gallery::renderGallery() ?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
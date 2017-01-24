<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Арендаторам");
?>

<?= \App\View::twig()->render('/arendatoram/index.twig') ?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
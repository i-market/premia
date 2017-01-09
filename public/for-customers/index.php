<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Покупателям");
?>

<?= \App\View::twig()->render('/for-customers/index.twig') ?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
<?
include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');

CHTTP::SetStatus("404 Not Found");
@define("ERROR_404","Y");

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle("404");
?>

<?
// set layout explicitly for `Iblock\Component\Tools::process404`
$APPLICATION->SetPageProperty(\App\PageProperty::LAYOUT, 'base.twig');
?>
<h1>Ошибка 404</h1>
<p>Страница не найдена или была удалена. Пожалуйста, проверьте URL-адрес.</p>

<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>


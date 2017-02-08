<?
use Bitrix\Main\Application;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("404");
$APPLICATION->SetPageProperty(\App\PageProperty::LAYOUT, 'error.twig');
$response = Application::getInstance()->getContext()->getResponse();
$response->setStatus('404 Not Found');
?>

<h1>Ошибка 404</h1>
<p>Страница не найдена или была удалена. Пожалуйста, проверьте URL-адрес.</p>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>

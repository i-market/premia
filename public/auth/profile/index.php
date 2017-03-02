<?
use App\PageProperty;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Личный кабинет");
$APPLICATION->SetPageProperty(PageProperty::LAYOUT, 'bare.twig');
?>

<? // TODO tmp whitespace fix, remove when breadcrumbs are done ?>
<section class="bread_crumbs">
    <div class="wrap">
        <div class="inner">
            <ul>
            </ul>
        </div>
    </div>
</section>
<?$APPLICATION->IncludeComponent(
	"bitrix:main.profile",
	"profile",
	Array(
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "N",
		"CHECK_RIGHTS" => "N",
		"SEND_INFO" => "N",
		"SET_TITLE" => "N",
		"USER_PROPERTY" => array(),
		"USER_PROPERTY_NAME" => ""
	)
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
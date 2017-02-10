<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Регистрация");
?>

<?$APPLICATION->IncludeComponent(
    "bitrix:main.register",
    "",
    Array(
        "AUTH" => "Y",
        "REQUIRED_FIELDS" => array("EMAIL"),
        "SET_TITLE" => "N",
        "SHOW_FIELDS" => array("EMAIL"),
        "SUCCESS_PAGE" => \App\Auth::profilePath(),
        "USER_PROPERTY" => array(),
        "USER_PROPERTY_NAME" => "",
        "USE_BACKURL" => "N"
    )
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
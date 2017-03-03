<?
$arUrlRewrite = array(
	array(
		"CONDITION" => "#^/press-center/publications/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/press-center/publications/index.php",
	),
	array(
		"CONDITION" => "#^/api/.*#",
		"RULE" => "",
		"ID" => "",
		"PATH" => "/api/routes.php",
	),
);

?>
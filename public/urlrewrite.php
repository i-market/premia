<?
$arUrlRewrite = array(
	array(
		"CONDITION" => "#^/auth/profile/vote/([0-9]+)/([0-9]+)/#",
		"RULE" => "IBLOCK_ID=\$1&ELEMENT_ID=\$2",
		"ID" => "",
		"PATH" => "/auth/profile/vote/index.php",
	),
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
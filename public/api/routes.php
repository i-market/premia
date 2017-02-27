<?php

use Klein\Klein;

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

$router = new Klein();
$router->with('/api/user', function () use ($router) {
    $router->respond('POST', '/signup', function($request, $response) {
        return '';
    });
});
$router->dispatch();

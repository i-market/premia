<?php

use App\User;
use Klein\Klein;

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

$router = new Klein();
$router->with('/api/user', function () use ($router) {
    $router->respond('POST', '/signup', function($request, $response) {
        global $USER;
        $params = $request->params(array('full-name', 'company', 'email', 'phone', 'password', 'password-confirmation'));
        $name = User::parseFullName(params['full-name']);
        $message = $USER->Register($params['email'], $name['FIRST_NAME'], $name['LAST_NAME'],
            $params['password'], $params['password-confirmation'], $params['email']);
        return $response->json(array_change_key_case($message, CASE_LOWER));
    });
});
$router->dispatch();

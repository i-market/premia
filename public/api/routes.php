<?php

use App\App;
use App\MailEvent;
use App\User;
use Core\Form;
use Klein\Klein;

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

$router = new Klein();
$router->with('/api', function () use ($router) {
    $router->with('/user', function () use ($router) {
        $router->respond('POST', '/signup', function($request, $response) {
            global $USER;
            $params = $request->params(array('full-name', 'company', 'email', 'phone', 'password', 'password-confirmation'));
            $name = User::parseFullName(params['full-name']);
            $message = $USER->Register($params['email'], $name['FIRST_NAME'], $name['LAST_NAME'],
                $params['password'], $params['password-confirmation'], $params['email']);
            return $response->json(array_change_key_case($message, CASE_LOWER));
        });
    });
});
foreach (App::formSpecs() as $spec) {
    $route = Form::formRoute($spec, function($params, $errors, $response) {
        if (count($errors) === 0) {
            $event = array_merge(array_change_key_case($params, CASE_UPPER), array(
                'EMAIL_FROM' => App::mailFrom(),
                'EMAIL_TO' => App::mailTo()
            ));
            App::sendMailEvent(MailEvent::CONTACT_FORM, App::SITE_ID, $event);
        }
        return $response->json(array('errors' => (object) $errors));
    });
    // mutate
    $router->respond($route['method'], $route['path'], $route['handler']);
}
$router->dispatch();

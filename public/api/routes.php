<?php

use App\App;
use App\MailEvent;
use App\User;
use Core\Form;
use Klein\Klein;

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

$formSpecs = App::formSpecs();
$signupRoute = Form::formRoute($formSpecs['signup'], function($params, $errors, $response) {
    global $USER;
    $message = array();
    if (count($errors) === 0) {
        $name = User::parseFullName($params['full-name']);
        // mutate
        $login = $params['email'];
        $message = $USER->Register($login, $name['FIRST_NAME'], $name['LAST_NAME'],
            $params['password'], $params['password-confirmation'], $params['email']);
        $isSuccess = $message['TYPE'] === 'OK';
        if ($isSuccess) {
            $user = CUser::GetByLogin($login)->GetNext();
            $USER->Update($user['ID'], array(
                'SECOND_NAME' => $name['PATRONYMIC'],
                'WORK_COMPANY' => $params['company'],
                'PERSONAL_PHONE' => $params['phone']
            ));
        }
    }
    return $response->json(array(
        'errors' => (object) $errors,
        'bxMessage' => (object) array_change_key_case($message, CASE_LOWER)
    ));
});
$router = new Klein();
$router->with('/api', function () use ($router, $signupRoute) {
    $router->with('/user', function () use ($router, $signupRoute) {
        $router->respond('POST', '/signup', $signupRoute['handler']);
    });
});
$route = Form::formRoute($formSpecs['contact'], function($params, $errors, $response) {
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
$router->dispatch();

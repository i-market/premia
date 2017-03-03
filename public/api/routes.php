<?php

use App\Api;
use App\App;
use App\MailEvent;
use App\User;
use Bitrix\Main\Localization\Loc;
use Core\Form;
use Klein\Klein;
use Core\Underscore as _;

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

// TODO move handlers to `Api`
$formSpecs = App::formSpecs();
$signupRoute = Form::formRoute($formSpecs['signup'], function($params, $errors, $response) {
    global $USER;
    $message = array();
    if (count($errors) === 0) {
        // mutate
        $login = $params['email'];
        // bitrix user first name = full name
        $message = $USER->Register($login, $params['full-name'], '',
            $params['password'], $params['password-confirmation'], $params['email']);
        $isSuccess = $message['TYPE'] === 'OK';
        if ($isSuccess) {
            $user = CUser::GetByLogin($login)->GetNext();
            $USER->Update($user['ID'], array(
                'WORK_COMPANY' => $params['company'],
                'PERSONAL_PHONE' => $params['phone']
            ));
            $msg = Loc::getMessage('USER_REGISTERED_SIMPLE');
            CUser::SendUserInfo($user['ID'], App::SITE_ID, $msg);
        }
    }
    return $response->json(Api::formResponse($errors, $message));
});
$router = new Klein();
$router->with('/api', function () use ($router, $signupRoute) {
    $router->with('/user', function () use ($router, $signupRoute) {
        $router->respond('POST', '/signup', $signupRoute['handler']);
        $router->respond('POST', '/login', function($request, $response) {
            global $USER;
            $params = $request->params(array('email', 'password', 'remember'));
            $login = $params['email'];
            $messageOrTrue = $USER->Login($login, $params['password'], $params['remember'] === 'on' ? 'Y' : 'N');
            $message = $messageOrTrue === true ? array() : $messageOrTrue;
            return $response->json(array_merge(
                array('isLoggedIn' => $messageOrTrue === true),
                Api::formResponse(array(), $message)
            ));
        });
        $router->respond('POST', '/reset', function($request, $response) {
            $params = $request->params(array('email'));
            $login = $params['email'];
            $message = CUser::SendPassword($login, $params['email']);
            return $response->json(Api::formResponse(array(), $message));
        });
        $router->respond('POST', '/profile', function($request, $response) {
            global $USER;
            $params = $request->params();
            // TODO sanitize
            $fields = $params;
            $isSuccess = $USER->Update($USER->GetID(), $fields);
            return $response->json(array(
                'isSuccess' => $isSuccess,
                'errorMessageMaybe' => $isSuccess ? null : $USER->LAST_ERROR
            ));
        });
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

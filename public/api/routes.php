<?php

use App\Api;
use App\App;
use App\ApplicationForm;
use App\Gallery;
use App\MailEvent;
use App\Messages;
use App\User;
use Bitrix\Main\Localization\Loc;
use Core\Form;
use Klein\Klein;
use Core\Underscore as _;
use Core\Strings as str;

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

// TODO move handlers to `Api`
// TODO extract business logic
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
            $userGroups = _::append(CUser::GetUserGroup($user['ID']), User::NOMINEE_GROUP);
            CUser::SetUserGroup($user['ID'], $userGroups);
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
    $router->respond('GET', '/download/[i:id]', function($request, $response) {
        Api::downloadFile($request->id);
    });
    $router->respond('GET', '/gallery/[i:id].html', function($request, $response) {
        return Gallery::renderGallerySlider($request->id, null);
    });
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
            if ($message['TYPE'] === 'OK') {
                // mutate
                $message['MESSAGE'] = 'Ссылка для восстановления пароля выслана на вашу почту.';
            }
            return $response->json(Api::formResponse(array(), $message));
        });
        $router->with('/profile', function () use ($router) {
            $router->respond('POST', '', function($request, $response) {
                global $USER;
                $params = $request->params();
                // TODO sanitize params
                $fields = str::isEmpty($params['PASSWORD'])
                    ? _::remove($params, array('PASSWORD', 'CONFIRM_PASSWORD'))
                    : $params;
                // TODO refactor: ad-hoc
                // make sure `WORK_COMPANY` is non-empty, it's used elsewhere (to identify application forms)
                $fields = str::isEmpty($fields['WORK_COMPANY']) ? _::remove($fields, 'WORK_COMPANY') : $fields;
                $isSuccess = $USER->Update($USER->GetID(), $fields);
                return $response->json(array(
                    'isSuccess' => $isSuccess,
                    'errorMessageMaybe' => $isSuccess ? null : $USER->LAST_ERROR
                ));
            });
            $router->respond('POST', '/application', function($request, $response) {
                return $response->json(Api::handleApplication($request));
            });
            $router->respond('POST', '/vote', function($request, $response) {
                global $USER;
                if (App::state()['VOTES_LOCKED']) {
                    trigger_error('someone tried to change a locked vote', E_USER_WARNING);
                    return $response->json(array(
                        'type' => 'error',
                        'message' => Messages::VOTES_LOCKED
                    ));
                } else {
                    // TODO sanitize params
                    $params = $request->params();
                    $formIblockId = intval($params['form']['iblock_id']);
                    // make sure there is no shenanigans going on
                    assert(in_array($formIblockId, ApplicationForm::iblockIds()));
                    $formId = intval($params['form']['id']);
                    $scores = array_values(_::mapValues(_::get($params, 'vote.scores'), function($v, $k) {
                        $propId = intval(_::last(explode(':', $k)));
                        return array('PROPERTY_ID' => $propId, 'VALUE' => intval($v));
                    }));
                    $voteIblockId = ApplicationForm::voteIblockId($formIblockId);
                    $voteIdMaybe = _::get($params, 'vote.id');
                    $el = new CIBlockElement();
                    $propValues = array_reduce($scores, function($values, $score) {
                        return _::set($values, $score['PROPERTY_ID'], array(
                            'VALUE' => $score['VALUE']
                        ));
                    }, array());
                    $fields = array(
                        'IBLOCK_ID' => $voteIblockId,
                        'NAME' => $params['form']['display_name'],
                        'PROPERTY_VALUES' => $propValues + array(
                                'USER' => $USER->GetID(),
                                'FORM' => $formId
                            )
                    );
                    $result = $voteIdMaybe === null
                        ? $el->Add($fields)
                        : $el->Update($voteIdMaybe, $fields);
                    return $response->json(Api::iblockOpResponse($el, $result));
                }
            });
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

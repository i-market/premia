<?php
// composer
$autoload = $_SERVER['DOCUMENT_ROOT'].'/private/vendor/autoload.php';
require_once $autoload;

use Klein\Klein;

class App {
    const ADMIN_EMAIL = 'novikov@i-market.ru';
}

$router = new Klein();
$router->with('/api', function () use ($router) {
    $router->respond('POST', '/callback-request', function($request, $response) {
        $params = $request->params(array('name', 'phone', 'body'));
        $mail = new PHPMailer();
        $mail->addAddress(App::ADMIN_EMAIL);
        $mail->Subject = 'Here is the subject';
        $mail->Body = var_export($params, true);
        $mail->send();
        return $response->json(array('errors' => array()));
    });
});
$router->dispatch();

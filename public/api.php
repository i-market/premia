<?php
// composer
$autoload = $_SERVER['DOCUMENT_ROOT'].'/private/vendor/autoload.php';
require_once $autoload;

class App {
    const ADMIN_EMAIL = 'novikov@i-market.ru';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fn = $_POST['fn'];
    if ($fn === 'callback-request') {
        $params = $_POST;
        $mail = new PHPMailer();
        $mail->CharSet = 'UTF-8';
        $mail->setFrom('postmaster@domodedovo.nichost.ru', 'Четыре сезона');
        $mail->addAddress(App::ADMIN_EMAIL);
        $mail->Subject = 'На сайте заказали обратный звонок';
        $mail->Body = 'Имя: '.$params['name'].PHP_EOL.'Телефон: '.$params['phone'].PHP_EOL.'Сообщение: '.$params['message'];
        $mail->send();
    }
}
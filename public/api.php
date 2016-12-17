<?php
// composer
$autoload = $_SERVER['DOCUMENT_ROOT'].'/private/vendor/autoload.php';
require_once $autoload;

class App {
    const ADMIN_EMAIL = 'novikov@i-market.ru';

    static function newMail() {
        $mail = new PHPMailer();
        $mail->CharSet = 'UTF-8';
        $mail->setFrom('postmaster@domodedovo.nichost.ru', 'Четыре сезона');
        $mail->addAddress(App::ADMIN_EMAIL);
        return $mail;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fn = $_POST['fn'];
    if ($fn === 'callback-request') {
        $params = $_POST;
        $mail = App::newMail();
        $mail->Subject = 'На сайте заказали обратный звонок';
        $mail->Body = 'Имя: '.$params['name'].PHP_EOL
            .'Телефон: '.$params['phone'].PHP_EOL
            .'Сообщение: '.$params['message'];
        $mail->send();
    } elseif ($fn === 'message') {
        $params = $_POST;
        $mail = App::newMail();
        $mail->Subject = 'С сайта отправлено письмо';
        $mail->Body = 'Имя: '.$params['name'].PHP_EOL
            .'Email: '.$params['email'].PHP_EOL
            .'Сообщение: '.$params['message'];
        $mail->send();
    }
}
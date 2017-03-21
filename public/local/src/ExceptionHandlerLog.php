<?php

namespace App;

use Core\App;
use Raven_Client;

class ExceptionHandlerLog extends \Bitrix\Main\Diag\ExceptionHandlerLog {
    private $client = null;

    public function write($exception, $logType) {
        if (function_exists('curl_init')) {
            // TODO provide some context to sentry (e.g. user id)
            $ret = $this->client->captureException($exception, array(
                'logType' => $logType
            ));
            return $ret;
        }
    }

    public function initialize(array $options) {
        $dsn = $options['sentry_dsn'];
        $this->client = new Raven_Client($dsn, array(
            'environment' => App::env()
        ));
    }
}

<?php

use Dotenv\Dotenv;

$localDir = $_SERVER['DOCUMENT_ROOT'].'/local';

// composer
require $localDir.'/vendor/autoload.php';

// load environment variables from .env to getenv(), $_ENV and $_SERVER
if (file_exists($localDir.'/.env')) {
    $dotenv = new Dotenv($localDir);
    $dotenv->load();
}

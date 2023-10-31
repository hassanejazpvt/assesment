<?php

require realpath('vendor/autoload.php');

use Hassan\Assesment\Bootstrap;
use Hassan\Assesment\Core\Error;
use Hassan\Assesment\Core\ExceptionHandler;

try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
} catch (\Exception $e) {
    die('Make sure .env file is present.');
}

define('BASE_PATH', dirname(__FILE__));
define('SRC_PATH', realpath(BASE_PATH . '/src'));

require realpath('src/bootstrap.php');

$app = new Bootstrap();
try {
    $app->run();
} catch (Error $e) {
    (new ExceptionHandler($e))();
}
exit();

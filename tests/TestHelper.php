<?php

use Phalcon\Di;
use Phalcon\Di\FactoryDefault;
use Phalcon\Loader;

ini_set("display_errors", 1);
error_reporting(E_ALL);

define("ROOT_PATH", __DIR__);

set_include_path(
    ROOT_PATH . PATH_SEPARATOR . get_include_path()
);

// Required for phalcon/incubator
include __DIR__ . "/../vendor/autoload.php";

/**
 * ==============================================================
 * Load the .env File
 * =============================================================
 */
try {
    $dotenv = new Symfony\Component\Dotenv\Dotenv();
    $dotenv->load('../.env');
} catch (Exception $e) {
    die('Missing required .env file.');
}

require_once('../config/constants.php');
$config = include CONFIG_DIR . "config.php";
$api = include CONFIG_DIR . "api.php";
include CONFIG_DIR . "loader.php";
include CONFIG_DIR . "services.php";
require_once APP_DIR . 'functions.php';

//print_r($di);

// Use the application autoloader to autoload the classes
// Autoload the dependencies found in composer
//$loader = new Loader();
//
//$loader->registerDirs(
//    [
//        ROOT_PATH,
//    ]
//);
//
//$loader->register();
//
//$di = new FactoryDefault();
////
//Di::reset();
//
//$di->set('modelsManager', function() {
//  return new \Phalcon\Mvc\Model\Manager();
//});

// Add any needed services to the DI here

//Di::setDefault($di);

$application = new \Phalcon\Mvc\Application($di);

$_SESSION = [];

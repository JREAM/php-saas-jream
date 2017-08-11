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

/**
 * ==============================================================
 * Required for phalcon/incubator
 * =============================================================
 */
$base_dir = dirname(__DIR__);
include $base_dir . "/vendor/autoload.php";

/**
 * ==============================================================
 * Load the .env File
 * =============================================================
 */
try {
    $dotenv = new Symfony\Component\Dotenv\Dotenv();
    $dotenv->load($base_dir . '/.env');
} catch (Exception $e) {
    die('Missing required .env file.');
}

/**
 * ==============================================================
 * Load the Config Constants for everything
    * ==============================================================
 */
if (!file_exists(dirname(__DIR__) . '/.env')) {
    die ("Environment File Required.");
}

// Configuration Overwrite Inclusion
require dirname(__DIR__) . '/config/constants.php';

$config = include CONFIG_DIR . "config.php";
$api = include CONFIG_DIR . "api.php";
include CONFIG_DIR . "loader.php";
include CONFIG_DIR . "services.php";
require_once APP_DIR . 'functions.php';


/**
 * ==============================================================
 * Timezone (Always UTC)
 * ==============================================================
 */
date_default_timezone_set(DEFAULT_TIMEZONE);

/**
 * ==============================================================
 * Use the application autoloader to autoload the classes
 * Autoload the dependencies found in composer
 * =============================================================
 */
$loader = new Loader();


$loader->registerDirs(
    [
        ROOT_PATH,
    ]
);

$loader->register();

$di = new FactoryDefault();

Di::reset();

// Add any needed services to the DI here

Di::setDefault($di);

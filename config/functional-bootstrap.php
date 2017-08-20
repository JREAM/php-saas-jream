<?php

/**
 * ==============================================================
 * Load Composer
 * =============================================================
 */
$base_dir = dirname(__DIR__);
$autoload_file = $base_dir . "/vendor/autoload.php";

if (!file_exists($autoload_file)) {
    die('Required: $ composer install');
}
require_once $autoload_file;

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
 * Load the Constants
 * =============================================================
 */
// Configuration Overwrite Inclusion
// @important This must come first!
require dirname(__DIR__) . '/config/constants.php';

/**
 * ==============================================================
 * Read the configuration
 * =============================================================
 */
$config = include CONFIG_DIR . "config.php";
$api = include CONFIG_DIR . "api.php";

/**
 * ==============================================================
 * Read phalcon auto-loader
 * =============================================================
 */
include CONFIG_DIR . "loader.php";


/**
 * ==============================================================
 * Read services
 * =============================================================
 */
include CONFIG_DIR . "services.php";


/**
 * ==============================================================
 * Custom functions after everything has loaded
 * =============================================================
 */
require_once APP_DIR . 'functions.php';


//return $di;
return new \Phalcon\Mvc\Application($di);

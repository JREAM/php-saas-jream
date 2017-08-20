<?php
/**
 * ==============================================================
 * @important Below must be in the public index.php page after
 *            this file is included.
 *
 *   echo $application->handle()->getContent();
 * =============================================================
 */


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
 * Cache DIR Check
 * =============================================================
 */
if (!is_writable(CACHE_DIR)) {
    die('Cache dir is not writable.');
}

/**
 * ==============================================================
 * Phalcon Bootstrap
 * =============================================================
 */
try {


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

    /**
     * ==============================================================
     * Handle the request
     * =============================================================
     */
    $application = new \Phalcon\Mvc\Application($di);
    echo $application->handle()->getContent();

} catch (\Exception $e) {

    if (PHP_SAPI === 'cli') {
        die($e->getMessage());
    }

    /**
     * ==============================================================
     * Non Live: Show Local Error (Or Whoops Appears)
     * =============================================================
     */
    if (\STAGE != 'live') {
        echo '<pre>';
        echo "Message: {$e->getMessage()} <br>";
        echo "File: {$e->getFile()}<br>";
        echo "Line: {$e->getLine()}<br>";
        echo $e->getTraceAsString();
        echo '</pre>';
        exit;
    }

    /**
     * ==============================================================
     * LIVE: Log Sentry Error
     * =============================================================
     */
    $di->get('sentry')->captureException($e);

    // Flash a message and go back home
    echo 'A fatal error occured, we have logged it and will look into it.';
    exit;


}

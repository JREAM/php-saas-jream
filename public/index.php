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
 * ==============================================================
 * Error Reporting
 * =============================================================
 */
error_reporting(E_ALL); // Log all errors


/**
 * Load the Config Constants for everything
 * =============================================================
 */
if (!file_exists(dirname(__DIR__) . '/.env')) {
    die ("Environment File Required.");
}

// Configuration Overwrite Inclusion
require dirname(__DIR__) . '/config/constants.php';



/**
 * ==============================================================
 * Timezone (Always UTC)
 * =============================================================
 */
date_default_timezone_set(DEFAULT_TIMEZONE);


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
     * Read auto-loader
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
     * Cache DIR Check
     * =============================================================
     */
    if (!is_writable(CACHE_DIR)) {
        $di->get('sentry')->captureException('Cache Dir is not writable.');
        echo 'Cache dir is not writable.';
        exit;
    }

    /**
     * ==============================================================
     * Handle the request
     * =============================================================
     */
    $application = new \Phalcon\Mvc\Application($di);
    echo $application->handle()->getContent();

} catch (\Exception $e) {

    if (\STAGE == 'live') {
        /**
         * ==============================================================
         * LIVE: Log Sentry Error
         * =============================================================
         */
        $di->get('sentry')->captureException($e);

        // Flash a message and go back home
        echo 'A fatal error occured, we have logged it and will look into it.';
        exit;
    } else {
        /**
         * ==============================================================
         * Other: Show Local Error (Or Whoops Appears)
         * =============================================================
         */
        echo '<pre>';
        echo "Message: {$e->getMessage()} <br>";
        echo "File: {$e->getFile()}<br>";
        echo "Line: {$e->getLine()}<br>";
        echo $e->getTraceAsString();
        echo '</pre>';
        exit;
    }

}

// End of File
// --------------------------------------------------------------------

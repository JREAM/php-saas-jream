<?php

use Phalcon\Mvc\Application;

/**
 * ==============================================================
 * Load Environment (Composer Auto-Loader, Constants)
 *
 * @important   The Order of file loading is crucial.
 * =============================================================
 */
require_once realpath(dirname(__DIR__)) . '/app/config/env.php';

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
    $config = require_once APP_PATH . "config/config.php";
    $api    = require_once $config->get('configDir') . "api.php";


    /**
     * ==============================================================
     * Custom functions
     * =============================================================
     */

    require_once APP_PATH . 'functions.php';


    /**
     * ==============================================================
     * Read phalcon auto-loader
     * This uses the config.php
     * =============================================================
     */
    require_once $config->get('configDir') . "loader.php";


    /**
     * ==============================================================
     * Read services
     * =============================================================
     */
    require_once $config->get('configDir') . "services.php";

    /**
     * ==============================================================
     * Handle the request
     * =============================================================
     */
    $application = new Application($di);


    if (\APPLICATION_ENV === \APP_TEST) {
        return $application;
    }


    echo $application->handle()->getContent();

} catch (\Exception $e) {

    if (PHP_SAPI === 'cli') {
        die($e->getMessage());
    }


    /**
     * ==============================================================
     * LIVE: Log Sentry Error
     * =============================================================
     */
    if (\APPLICATION_ENV === \APP_PRODUCTION) {
        $di->get('sentry')->captureException($e);
        echo 'An unknown error occured. JREAM has been notified of this and will dispatch fixes soon.';
        exit;
    }


    /**
     * ==============================================================
     * Non Live: Show Local Error (Or Whoops Appears)
     * =============================================================
     */
    echo '<pre>';
    echo "Message: {$e->getMessage()} <br>";
    echo "File: {$e->getFile()}<br>";
    echo "Line: {$e->getLine()}<br>";
    echo $e->getTraceAsString();
    echo '</pre>';


}

<?php
/**
 * ==============================================================
 * Phalcon CLI Bootstrap
 * =============================================================
 */

use Phalcon\Di\FactoryDefault\Cli as CliDI;
use Phalcon\Cli\Console as ConsoleApp;

/**
 * ==============================================================
 * Load
 * =============================================================
 */
require_once realpath(dirname(__DIR__)) . '/config/env.php';


/**
 * ==============================================================
 * Read the configuration
 * =============================================================
 */
$config = require DOCROOT . "/config/config.php";
$api = require $config->get('configDir') . "api.php";

/**
 * ==============================================================
 * Read phalcon auto-loader
 * This uses the config.php
 * =============================================================
 */
require_once $config->get('configDir') . "loader.php";

/**
 * ==============================================================
 * @important
 * Do NOT Load Services
 * =============================================================
 */

/**
 * ==============================================================
 * Custom functions after everything has loaded
 * =============================================================
 */
require_once APP_PATH . '/functions.php';

/**
 * ==============================================================
 * Using the CLI factory default services container
 * =============================================================
 */
$di = new CliDI();

/**
 * ==============================================================
 * Add necessary DI Items
 * =============================================================
 */
$di->setShared('config', function () use ($config) {
    return $config;
});

$di->setShared('api', function () use ($api) {
    return $api;
});

$di->setShared('session', function () {
    $session = new \Phalcon\Session\Adapter\Files();

    $session->start();

    return $session;
});

/**
 * ==============================================================
 * Database Connection
 * =============================================================
 */
$di->set('db', function () use ($di, $config) {
    $eventsManager = $di->getShared('eventsManager');
    $eventsManager->attach('db', new \Middleware\Database());

    $database = new \Phalcon\Db\Adapter\Pdo\Mysql((array)$config->database);
    $database->setEventsManager($eventsManager);

    return $database;
});


// Create a console application
$console = new ConsoleApp();
$console->setDI($di);

/**
 * Process the console arguments
 */
$arguments = [];

foreach ($argv as $k => $arg) {
    if ($k === 1) {
        $arguments['task'] = $arg;
    }
    elseif ($k === 2) {
        $arguments['action'] = $arg;
    }
    elseif ($k >= 3) {
        $arguments['params'][] = $arg;
    }
}

try {
    // Handle incoming arguments
    $console->handle($arguments);
} catch (\Phalcon\Exception $e) {
    // Do Phalcon related stuff here
    // ..
    fwrite(STDERR, $e->getMessage() . PHP_EOL);
    exit(1);
} catch (\Throwable $throwable) {
    fwrite(STDERR, $throwable->getMessage() . PHP_EOL);
    exit(1);
} catch (\Exception $exception) {
    fwrite(STDERR, $exception->getMessage() . PHP_EOL);
    exit(1);
}

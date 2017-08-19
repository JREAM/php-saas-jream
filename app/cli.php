<?php

// Runs files in Tasks/*

use Phalcon\Di\FactoryDefault\Cli as CliDI;
use Phalcon\Cli\Console as ConsoleApp;
use Phalcon\Loader;


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



require_once __DIR__ . '/../config/constants.php';
require_once CONFIG_DIR . '/loader.php';
require_once CONFIG_DIR . '/config.php';
require_once CONFIG_DIR . '/api.php';
require_once APP_DIR . '/functions.php';

// Using the CLI factory default services container
$di = new CliDI();

/**
 * ==============================================================
 * Make Config and api Accessible where we have DI
 * =============================================================
 */
$di->setShared('config', function() use ($config) {
    return $config;
});

$di->setShared('api', function () use ($api) {
    return $api;
});


/**
 * ==============================================================
 * Session
 * =============================================================
 */
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
    $eventsManager->attach('db', new Middleware\Database());

    $database = new Phalcon\Db\Adapter\Pdo\Mysql((array) $config->database);
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
    } elseif ($k === 2) {
        $arguments['action'] = $arg;
    } elseif ($k >= 3) {
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

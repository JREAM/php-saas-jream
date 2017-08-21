<?php

/**
 * ==============================================================
 * Register the autoloader
 * =============================================================
 */
$loader = new \Phalcon\Loader();

$loader->registerNamespaces([
    'Component'             => $config->get('componentDir'),
    'Middleware'            => $config->get('controllersDir'),
    'Controllers'           => $config->get('controllersDir'),
    'Controllers\Dashboard' => $config->get('controllersDir') . 'dashboard/',
    'Controllers\Api'       => $config->get('controllersDir') . 'api/',
    'Library'               => $config->get('libraryDir'),
    'Plugins'               => $config->get('pluginsDir'),
    'Migrations'            => $config->get('migrationsDir'),
]);

$loader->registerClasses([
    'Phalcon' => DOCROOT . '/vendor/phalcon/incubator/Library/Phalcon/',
]);

$registerDirs = [
    $config->get('configDir'),
    $config->get('formsDir'),
    $config->get('modelsDir'),
];

// 1: For running unit tests
// 2: For the CLI Tasks
if (strtolower(\PHP_SAPI) === 'cli')
{
    // Auto Load the Tests Directory.
    $registerDirs[] = $config->get('testsDir');

    // Auto Load the Tasks
    $loader->registerNamespaces([
        'Tasks' => $config->get('tasksDir')
    ]);
}

// Register Remaining Directories
$loader->registerDirs($registerDirs);

// Finished
$loader->register();

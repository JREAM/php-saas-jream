<?php

/**
 * ==============================================================
 * Register the autoloader
 * =============================================================
 */
$loader = new \Phalcon\Loader();

$loader->registerNamespaces([
    'Controllers'           => $config->get('controllersDir'),
    'Controllers\Api'       => $config->get('controllersDir') . 'api/',
    'Controllers\Dashboard' => $config->get('controllersDir') . 'dashboard/',
    'Library'               => $config->get('libraryDir'),
    'Forms'                 => $config->get('formsDir'),
    'Middleware'            => $config->get('middlewareDir'),
    'Migrations'            => $config->get('migrationsDir'),
]);

$loader->registerClasses([
    'Phalcon' => DOCROOT . 'vendor/phalcon/incubator/Library/Phalcon/',
]);

$registerDirs = [
    $config->get('componentsDir'),
    $config->get('configDir'),
    $config->get('pluginsDir'),
    $config->get('modelsDir'),
    $config->get('tasksDir'),
];

// 1: For running unit tests
// 2: For the CLI Tasks
if (strtolower(\PHP_SAPI) === 'cli')
{
    // Auto Load the Tests Directory.
    $registerDirs[] = $config->get('testsDir');
}


// Register Remaining Directories
$loader->registerDirs($registerDirs);

// Finished
$loader->register();

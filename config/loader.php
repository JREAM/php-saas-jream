<?php

/**
 * ==============================================================
 * Register the autoloader
 * =============================================================
 */
$loader = new \Phalcon\Loader();

$loader->registerNamespaces([
    'Components'            => $config->get('componentsDir'),
    'Controllers'           => $config->get('controllersDir'),
    'Controllers\Api'       => $config->get('controllersDir') . 'api/',
    'Controllers\Dashboard' => $config->get('controllersDir') . 'dashboard/',
    'Library'               => $config->get('libraryDir'),
    'Middleware'            => $config->get('middlewareDir'),
    'Migrations'            => $config->get('migrationsDir'),
    'Plugins'               => $config->get('pluginsDir'),
    'Tasks'                 => $config->get('tasksDir'),
]);

$loader->registerClasses([
    'Phalcon' => DOCROOT . 'vendor/phalcon/incubator/Library/Phalcon/',
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
}


// Register Remaining Directories
$loader->registerDirs($registerDirs);

// Finished
$loader->register();

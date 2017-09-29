<?php

/**
 * ==============================================================
 * Register the autoloader
 * =============================================================
 */
$loader = new \Phalcon\Loader();

$loader->registerNamespaces([
    'Controllers'                  => $config->get('controllersDir'),
    'Controllers\Api'              => $config->get('controllersDir') . 'api/',
    'Controllers\Dashboard'        => $config->get('controllersDir') . 'dashboard/',
    'Library'                      => $config->get('libraryDir'),
    'Library\Volt'                 => $config->get('libraryDir') . 'volt/',
    'Library\Volt\Filters'         => $config->get('libraryDir') . 'volt/filters',
    'Library\Volt\Functions'       => $config->get('libraryDir') . 'volt/functions',
    'Library\Volt\Tags'            => $config->get('libraryDir') . 'volt/tags',
    'Forms'                        => $config->get('formsDir'),
    'Forms\Traits'                 => $config->get('formsDir') . 'traits/',
    'Plugins'                      => $config->get('pluginsDir'),
    'Plugins\Internationalization' => $config->get('pluginsDir') . 'Internationalization',
    'Middleware'                   => $config->get('middlewareDir'),
    'Migrations'                   => $config->get('migrationsDir'),
    'Models\Traits'                => $config->get('modelsDir') . 'traits/',
]);

$loader->registerClasses([
    'Phalcon' => DOCROOT . 'vendor/phalcon/incubator/Library/Phalcon/',
]);

$registerDirs = [
    $config->get('componentsDir'),
    $config->get('configDir'),
    $config->get('modelsDir'),
    $config->get('tasksDir'),
];

// 1: For running unit tests
// 2: For the CLI Tasks
if (strtolower(\PHP_SAPI) === 'cli') {
    // Auto Load the Tests Directory.
    $registerDirs[] = $config->get('testsDir');
}


// Register Remaining Directories
$loader->registerDirs($registerDirs);

// Finished
$loader->register();

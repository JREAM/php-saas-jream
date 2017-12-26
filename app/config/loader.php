<?php

/**
 * ==============================================================
 * Register the autoloader
 * =============================================================
 */
$loader = new \Phalcon\Loader();

$loader->registerNamespaces([
    'Controllers'                  => $config->application->controllersDir,
    'Controllers\Api'              => $config->application->controllersDir . 'api/',
    'Controllers\Dashboard'        => $config->application->controllersDir . 'dashboard/',
    'Library'                      => $config->application->libraryDir,
    'Library\Volt'                 => $config->application->libraryDir . 'volt/',
    'Library\Volt\Filters'         => $config->application->libraryDir . 'volt/filters',
    'Library\Volt\Functions'       => $config->application->libraryDir . 'volt/functions',
    'Library\Volt\Tags'            => $config->application->libraryDir . 'volt/tags',
    'Forms'                        => $config->application->formsDir,
    'Forms\Traits'                 => $config->application->formsDir . 'traits/',
    'Plugins'                      => $config->application->pluginsDir,
    'Plugins\Internationalization' => $config->application->pluginsDir . 'Internationalization',
    'Middleware'                   => $config->application->middlewareDir,
    'Migrations'                   => $config->application->migrationsDir,
    'Models\Traits'                => $config->application->modelsDir . 'traits/',
]);

$loader->registerClasses([
    'Phalcon' => DOCROOT . 'vendor/phalcon/incubator/Library/Phalcon/',
]);

$registerDirs = [
    $config->application->componentsDir,
    $config->application->configDir,
    $config->application->modelsDir,
    $config->application->tasksDir,
];

// 1: For running unit tests
// 2: For the CLI Tasks
if (strtolower(\PHP_SAPI) === 'cli') {
    // Auto Load the Tests Directory.
    $registerDirs[] = $config->application->testsDir;
}


// Register Remaining Directories
$loader->registerDirs($registerDirs);

// Finished
$loader->register();

<?php

/**
 * ==============================================================
 * Register the autoloader
 * =============================================================
 */
$loader = new \Phalcon\Loader();

// Register some namespaces
$loader->registerNamespaces(
    [
       "App\Components"    => COMPONENTS_DIR,
       "App\Controllers"    => CONTROLLERS_DIR . "/",
       "App\Controllers\Api"    => CONTROLLERS_DIR . "api/",
       "App\Controllers\Dashboard"    => CONTROLLERS_DIR . "dashboard/",
       "App\Library"    => LIBRARY_DIR . "library/",
       "App\Forms"    => FORMS_DIR . "forms/",
       "App\Middleware"    => MIDDLEWARE_DIR . "middleware/",
       "App\Models"    => MODELS_DIR . "models/",
       "App\Plugins"    => PLUGINS_DIR . "plugins/",
       "App\Tasks"    => CONTROLLERS_DIR . "tasks/",
    ]
);

$loader->registerClasses([
    'Phalcon'              => VENDOR_DIR . 'phalcon/incubator/Library/Phalcon/',
]);

// 1: For running unit tests
// 2: For the CLI Tasks
if (\PHP_SAPI == 'cli') {
    $loader->registerDirs([
        TESTS_DIR,
        TASKS_DIR
]   );
}


$loader->register();

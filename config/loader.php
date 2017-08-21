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
        "App\Components"            => COMPONENTS_DIR,
        "App\Controllers"           => CONTROLLERS_DIR,
        "App\Controllers\Api"       => CONTROLLERS_DIR . "api/",
        "App\Controllers\Dashboard" => CONTROLLERS_DIR . "dashboard/",
        "App\Library"               => LIBRARY_DIR,
        "App\Forms"                 => FORMS_DIR,
        "App\Middleware"            => MIDDLEWARE_DIR,
        "App\Models"                => MODELS_DIR,
        "App\Plugins"               => PLUGINS_DIR,
        "App\Tasks"                 => TASKS_DIR . "tasks/",
        "App\Tests"                 => BASE_DIR . "tests",
        "Phalcon"                   => VENDOR_DIR . 'phalcon/incubator/Library/Phalcon/',
    ]
);

// 1: For running unit tests
// 2: For the CLI Tasks
if (\PHP_SAPI == 'cli') {
    $loader->registerDirs([
        TESTS_DIR,
    ]);
}

$loader->register();

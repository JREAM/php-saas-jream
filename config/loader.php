<?php

/**
 * ==============================================================
 * Register the autoloader
 * =============================================================
 */
$loader = new \Phalcon\Loader();

$loader->registerClasses([
    'Component\Permission' => COMPONENTS_DIR . 'Permission.php',
    'Component\Helper'     => COMPONENTS_DIR . 'Helper.php',
    'Component\Email'      => COMPONENTS_DIR . 'Email.php',
    'Component\Cookies'    => COMPONENTS_DIR . 'Cookies.php',
    'Middleware\Database'  => MIDDLEWARE_DIR . 'Database.php',
    'Middleware\Dispatch'  => MIDDLEWARE_DIR . 'Dispatch.php',
    'Middleware\Ajax'      => MIDDLEWARE_DIR . 'Ajax.php',
    "Dashboard"            => CONTROLLERS_DIR . "dashboard/",
    "Api\V1"               => CONTROLLERS_DIR . "api/v1/",
    'Phalcon'              => VENDOR_DIR . 'phalcon/incubator/Library/Phalcon/',
]);

$registerDirs = [
    CONFIG_DIR,
    CONTROLLERS_DIR,
    FORMS_DIR,
    MIDDLEWARE_DIR,
    MODELS_DIR,
];

// 1: For running unit tests
// 2: For the CLI Tasks
if (\PHP_SAPI == 'cli') {
    $registerDirs[] = TESTS_DIR;
    $registerDirs[] = TASKS_DIR;
}

$loader->registerDirs($registerDirs);

$loader->register();

// End of File
// --------------------------------------------------------------

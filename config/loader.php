<?php

/**
 * ==============================================================
 * Register the autoloader
 * =============================================================
 */
$loader = new \Phalcon\Loader();

$loader->registerClasses([
    'Component\Permission'  => COMPONENTS_DIR . 'Permission.php',
    'Component\Helper'      => COMPONENTS_DIR . 'Helper.php',
    'Component\Email'       => COMPONENTS_DIR . 'Email.php',
    'Component\Cookies'     => COMPONENTS_DIR . 'Cookies.php',
    'Event\Database'        => EVENTS_DIR . 'Database.php',
    'Event\Dispatch'        => EVENTS_DIR . 'Dispatch.php',
]);

$loader->registerNamespaces([
   "Dashboard"  => CONTROLLERS_DIR . "dashboard/",
   'Phalcon'    => VENDOR_DIR . 'phalcon/incubator/Library/Phalcon/'
]);

$registerDirs = [
    CONFIG_DIR,
    CONTROLLERS_DIR,
    FORMS_DIR,
    EVENTS_DIR,
    MODELS_DIR,
];

// When running unit tests, the SAPI CLI should autoload the tests folder.
if (\PHP_SAPI == 'cli') {
    $registerDirs[] = TESTS_DIR;
}

$loader->registerDirs($registerDirs);

$loader->register();

// End of File
// --------------------------------------------------------------

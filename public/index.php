<?php
// --------------------------------------------------------------------
// Error Reporting
// --------------------------------------------------------------------
error_reporting(E_ALL); // Log all errors

// --------------------------------------------------------------------
// Load the Config Constants for everything
// --------------------------------------------------------------------

if ( ! file_exists(dirname(__DIR__) . '/config/config-overwrite.php' )) {
    die ("Configuration Required.");
}
// Config Overwrite Includsion
require dirname(__DIR__) . '/config/config-overwrite.php';

require dirname(__DIR__) . '/config/constants.php';

// Overwrite constants
$constants = Overwrite::getConstants($constants);
foreach ($constants as $key => $value) {
    defined ($key) or define($key, $value);
}

// --------------------------------------------------------------------
// Timezone
// --------------------------------------------------------------------
date_default_timezone_set(DEFAULT_TIMEZONE);


// --------------------------------------------------------------------
// Phalcon Bootstrap
// --------------------------------------------------------------------
try {

    // --------------------------------------------------------------------
    // Load Composer
    // --------------------------------------------------------------------
    $autoload_file = VENDOR_DIR . "autoload.php";

    if (!file_exists($autoload_file)) {
        throw new \Exception('Required: $ composer install');
    }
    require_once $autoload_file;

    // --------------------------------------------------------------------
    // Read the configuration
    // (These files detect if an overwrite file is included for live/local)
    // --------------------------------------------------------------------
    $config = include CONFIG_DIR . "config.php";
    $api    = include CONFIG_DIR . "api.php";

    $config = Overwrite::getConfig($config);
    $api = Overwrite::getApi($api);

    // --------------------------------------------------------------------
    // Read auto-loader
    // --------------------------------------------------------------------
    include CONFIG_DIR . "loader.php";

    // --------------------------------------------------------------------
    // Read services
    // --------------------------------------------------------------------
    include CONFIG_DIR . "services.php";

    // -----------------------------------
    // Custom functions after everything has loaded
    // -----------------------------------
    require_once APP_DIR . 'functions.php';

    // --------------------------------------------------------------------
    // Handle the request
    // --------------------------------------------------------------------
    $application = new \Phalcon\Mvc\Application($di);
    echo $application->handle()->getContent();

} catch (\Exception $e) {

    if (\STAGE == 'live') {
        // Log data to getSentry
        $di->get('sentry')->captureException($e);

        // Flash a message and go back home
        echo 'A fatal error occured, we have logged it and will look into it.';
        exit;
    } else {
        echo '<pre>';
        echo "Message: {$e->getMessage()} <br>";
        echo "File: {$e->getFile()}<br>";
        echo "Line: {$e->getLine()}<br>";
        echo $e->getTraceAsString();
        echo '</pre>';
        exit;
    }

    if (!is_writable(CACHE_DIR)) {
        $di->get('sentry')->captureException('Cache Dir is not writable.');
        echo 'Cache dir is not writable.';
        exit;
    }


}
// End of File
// --------------------------------------------------------------------

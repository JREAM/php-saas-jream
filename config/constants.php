<?php
// --------------------------------------------------------------
// Constants
//
// constants-overwrite.php is possible, so these items check if
// they are not yet defined first.
// --------------------------------------------------------------
if (file_exists(__DIR__ . '/constants-overwrite.php')) {
    require __DIR__ . '/constants-overwrite.php';
}

// --------------------------------------------------------------
// Timezone
// --------------------------------------------------------------
defined ('DEFAULT_TIMEZONE') or define('DEFAULT_TIMEZONE', 'UTC');

// --------------------------------------------------------------
// Settings
// --------------------------------------------------------------
defined ('STAGE')    or define('STAGE', 'local');
defined ('URL')      or define('URL', 'projects/jream.com');
defined ('BASE_URI') or define('BASE_URI', '/jream.com/');
defined ('HTTPS')    or define('HTTPS', false);

// --------------------------------------------------------------
// Absolute Paths
// --------------------------------------------------------------
// (Used in Services.php)
defined ('BASE_DIR') or define('BASE_DIR', dirname(__DIR__) . '/'); // Append Slash

// --------------------------------------------------------------
// Core Paths
// --------------------------------------------------------------
defined ('APP_DIR')     or define('APP_DIR',    BASE_DIR . 'app/');
defined ('CACHE_DIR')   or define('CACHE_DIR',  BASE_DIR . 'cache/');
defined ('VENDOR_DIR')  or define('VENDOR_DIR', BASE_DIR . 'vendor/');

defined ('CONFIG_DIR')  or define('CONFIG_DIR', BASE_DIR . 'config/');

// --------------------------------------------------------------
// MVC Paths
// --------------------------------------------------------------
defined ('MODELS_DIR')      or define('MODELS_DIR',      APP_DIR . 'models/');
defined ('CONTROLLERS_DIR') or define('CONTROLLERS_DIR', APP_DIR . 'controllers/');
defined ('EVENTS_DIR')      or define('EVENTS_DIR',      APP_DIR . 'events/');

// --------------------------------------------------------------
// MVC Extra Paths
// --------------------------------------------------------------
defined ('COMPONENTS_DIR') or define('COMPONENTS_DIR',  APP_DIR . 'components/');
defined ('FORMS_DIR') or define('FORMS_DIR',            APP_DIR . 'forms/');

// --------------------------------------------------------------
// Extra Paths
// --------------------------------------------------------------
defined ('RESOURCES_DIR') or define('RESOURCES_DIR', BASE_DIR . 'resources/');
defined ('EMAILS_DIR') or define('EMAILS_DIR', RESOURCES_DIR . 'emails/');

// End of File
// --------------------------------------------------------------

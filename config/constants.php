<?php
/**
 * This is generated into constants in public/index.php
 * Its created this way so it can be overwritten if needed
 */
$constants = new \Phalcon\Config([

    // --------------------------------------------------------------
    // Timezone
    // --------------------------------------------------------------
    'DEFAULT_TIMEZONE' => 'UTC',

    // --------------------------------------------------------------
    // Settings
    // --------------------------------------------------------------
    'STAGE' => 'local',
    'URL' => 'projects/jream.com',
    'BASE_URI' => '/jream.com/',
    'HTTPS' => false,

    // --------------------------------------------------------------
    // Absolute Paths
    // --------------------------------------------------------------
    // (Used in Services.php)
    'BASE_DIR' => dirname(__DIR__) . '/', // Append Slash
]);

// --------------------------------------------------------------
// Core Paths
// --------------------------------------------------------------
$constants['APP_DIR'] = $constants['BASE_DIR'] . 'app/';
$constants['CACHE_DIR'] = $constants['BASE_DIR'] . 'cache/';
$constants['VENDOR_DIR'] = $constants['BASE_DIR'] . 'vendor/';
$constants['CONFIG_DIR'] = $constants['BASE_DIR'] . 'config/';

// --------------------------------------------------------------
// MVC Paths
// --------------------------------------------------------------
$constants['MODELS_DIR'] = $constants['BASE_DIR'] . 'models/';
$constants['CONTROLLERS_DIR'] = $constants['BASE_DIR'] . 'controllers/';
$constants['EVENTS_DIR'] = $constants['BASE_DIR'] . 'events/';

// --------------------------------------------------------------
// MVC Extra Paths
// --------------------------------------------------------------
$constants['COMPONENTS_DIR'] = $constants['APP_DIR'] . 'components/';
$constants['FORMS_DIR'] = $constants['APP_DIR'] . 'forms/';

// --------------------------------------------------------------
// Extra Paths
// --------------------------------------------------------------
$constants['RESOURCES_DIR'] = $constants['BASE_DIR'] . 'resources/';
$constants['EMAILS_DIR'] = $constants['RESOURCES_DIR'] . 'emails/';


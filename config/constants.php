<?php

/**
 * ==============================================================
 * Website Constants
 * =============================================================
 * =
 * Based on the .env configuration, it will use the values below
 * by default for test environments, or the live settings some of
 * which are provided in the .env file.
 *
 * --------------------------------------------------------------
 */


/**
 * ==============================================================
 * These rely on the .env file in the source path
 * =============================================================
 */

$constants = [];

// --------------------------------------------------------------
// Timezone: This should always be UTC
// --------------------------------------------------------------
$constants['DEFAULT_TIMEZONE'] = 'UTC';

$constants['STAGE'] = getenv('STAGE');
$constants['URL'] = getenv('URL');
$constants['BASE_URI'] = getenv('BASE_URI');
$constants['HTTPS'] = getenv('HTTPS');

// --------------------------------------------------------------
// Absolute Paths: Used in services.php
// --------------------------------------------------------------
$constants['BASE_DIR'] = dirname(__DIR__) . '/';// Append Slash


/**
 * ==============================================================
 * Core Paths
 * =============================================================
 */
$constants['APP_DIR'] = $constants['BASE_DIR'] . 'app/';
$constants['CACHE_DIR'] = $constants['BASE_DIR'] . 'cache/';
$constants['VENDOR_DIR'] = $constants['BASE_DIR'] . 'vendor/';
$constants['CONFIG_DIR'] = $constants['BASE_DIR'] . 'config/';


/**
 * ==============================================================
 * MVC Paths
 * =============================================================
 */
$constants['MODELS_DIR'] = $constants['APP_DIR'] . 'models/';
$constants['CONTROLLERS_DIR'] = $constants['APP_DIR'] . 'controllers/';
$constants['EVENTS_DIR'] = $constants['APP_DIR'] . 'events/';


/**
 * ==============================================================
 * MVC Extra Paths
 * =============================================================
 */
$constants['COMPONENTS_DIR'] = $constants['APP_DIR'] . 'components/';
$constants['FORMS_DIR'] = $constants['APP_DIR'] . 'forms/';


/**
 * ==============================================================
 * Additional Paths
 * =============================================================
 */
$constants['RESOURCES_DIR'] = $constants['BASE_DIR'] . 'resources/';
$constants['EMAILS_DIR'] = $constants['RESOURCES_DIR'] . 'emails/';


/**
 * ==============================================================
 * Set all the Constants
 * Based off the config.overwrite settings.
 * =============================================================
 */
foreach ($constants as $key => $value) {
    // Everything must be defined.
    if (!strlen($value)) {
        throw new InvalidArgumentException("Invalid Environment Configuration. $key must have a value.");
    }

    defined($key) or define($key, $value);
}

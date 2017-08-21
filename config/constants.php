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
// Used for Testing and All that
// --------------------------------------------------------------
$constants['APPLICATION_ENV'] = getenv('APPLICATION_ENV');

// --------------------------------------------------------------
// Timezone: This should always be UTC
// --------------------------------------------------------------
$constants['DEFAULT_TIMEZONE'] = getenv('DEFAULT_TIMEZONE');

$constants['URL'] = getenv('URL');
$constants['BASE_URI'] = getenv('BASE_URI');
$constants['HTTPS'] = getenv('HTTPS');

$constants['ERROR_REPORTING'] = getenv('ERROR_REPORTING');

// --------------------------------------------------------------
// Absolute Paths: Used in services.php
// --------------------------------------------------------------
$constants['BASE_DIR'] = dirname(__DIR__) . '/';// Append Slash


/**
 * ==============================================================
 * Core Paths
 * =============================================================
 */
$constants['APP_DIR'] = $constants['BASE_DIR'] . 'app' . DIRECTORY_SEPARATOR;
$constants['CACHE_DIR'] = $constants['BASE_DIR'] . 'cache' . DIRECTORY_SEPARATOR;
$constants['VENDOR_DIR'] = $constants['BASE_DIR'] . 'vendor' . DIRECTORY_SEPARATOR;
$constants['CONFIG_DIR'] = $constants['BASE_DIR'] . 'config' . DIRECTORY_SEPARATOR;
$constants['TESTS_DIR'] = $constants['BASE_DIR'] . 'tests' . DIRECTORY_SEPARATOR;


/**
 * ==============================================================
 * MVC Paths
 * =============================================================
 */
$constants['VIEWS_DIR'] = $constants['APP_DIR'] . 'views' . DIRECTORY_SEPARATOR;
$constants['MODELS_DIR'] = $constants['APP_DIR'] . 'models' . DIRECTORY_SEPARATOR;
$constants['CONTROLLERS_DIR'] = $constants['APP_DIR'] . 'controllers' . DIRECTORY_SEPARATOR;
$constants['MIDDLEWARE_DIR'] = $constants['APP_DIR'] . 'middleware' . DIRECTORY_SEPARATOR;
$constants['PLUGINS_DIR'] = $constants['APP_DIR'] . 'plugins' . DIRECTORY_SEPARATOR;


/**
 * ==============================================================
 * MVC Extra Paths
 * =============================================================
 */
$constants['COMPONENTS_DIR'] = $constants['APP_DIR'] . 'components' . DIRECTORY_SEPARATOR;
$constants['FORMS_DIR'] = $constants['APP_DIR'] . 'forms' . DIRECTORY_SEPARATOR;
$constants['LIBRARY_DIR'] = $constants['APP_DIR'] . 'library' . DIRECTORY_SEPARATOR;
$constants['TASKS_DIR'] = $constants['APP_DIR'] . 'tasks' . DIRECTORY_SEPARATOR;
$constants['MIGRATIONS_DIR'] = $constants['BASE_DIR'] . 'migrations' . DIRECTORY_SEPARATOR;


/**
 * ==============================================================
 * Additional Paths
 * =============================================================
 */
$constants['RESOURCES_DIR'] = $constants['BASE_DIR'] . 'resources' . DIRECTORY_SEPARATOR;
$constants['EMAILS_DIR'] = $constants['RESOURCES_DIR'] . 'emails' . DIRECTORY_SEPARATOR;

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


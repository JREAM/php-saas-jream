<?php

/**
 * ==============================================================
 * Error Reporting
 *
 * @important: Load AFTER constants
 * =============================================================
 */
error_reporting(E_ALL); // Log all errors


/**
 * ==============================================================
 * Timezone (Always UTC)
 *
 * @important: Load AFTER constants
 * =============================================================
 */
date_default_timezone_set(DEFAULT_TIMEZONE);

/**
 * ==============================================================
 * Website Config
 * =============================================================
 * =
 * Based on the .env configuration, it will use the values below
 * by default for test environments, or the live settings some of
 * which are provided in the .env file.
 *
 * --------------------------------------------------------------
 */

$config = new \Phalcon\Config([
    'title' => getenv('SITE_TITLE'),
    'session_hash'  => 'D0__2&$whatLORD$As4Sayy_)s5<E1+WilBeWe1lll2#', // Do not change this
    'cookie_hash' => '#_can$iSAY>let*US*EN~cryp_T-theCookieS!',
    'url_static' => 'https://d2qmoq5vnrtrov.cloudfront.net/',
    'email' => [
        'from_address' => getenv('EMAIL_FROM_ADDR'),
        'from_name'    => getenv('EMAIL_FROM_NAME'),
        'to_name'      => getenv('EMAIL_TO_NAME'),
        'to_question_address' => getenv('EMAIL_QUESTION_ADDR'),
        'to_contact_address'  => getenv('EMAIL_CONTACT_ADDR')
    ],
    'database' => [
        'adapter'  => getenv('DB_ADAPTER'),
        'host'     => getenv('DB_HOST'),
        'username' => getenv('DB_USERNAME'),
        'password' => getenv('DB_PASSWORD'),
        'dbname'   => getenv('DB_DATABASE')
    ],
    'application' => [
        'controllersDir' => CONTROLLERS_DIR,
        'modelsDir'      => MODELS_DIR,
        'migrationsDir'  => MIGRATIONS_DIR,
        'viewsDir'       => VIEWS_DIR,
        'pluginsDir'     => PLUGINS_DIR,
        'libraryDir'     => LIBRARY_DIR,
        'cacheDir'       => CACHE_DIR,
        'baseUri'        => dirname(__DIR__),
    ]
]);

return $config;

// End of File
// --------------------------------------------------------------

<?php

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

    'title'          => getenv('SITE_TITLE'),
    'session_hash'   => 'D0__2&$whatLORD$As4Sayy_)s5<E1+WilBeWe1lll2#', // Do not change this
    'cookie_hash'    => '#_can$iSAY>let*US*EN~cryp_T-theCookieS!',
    'url_static'     => 'https://d2qmoq5vnrtrov.cloudfront.net/',

    'email'       => [
        'from_address'        => getenv('EMAIL_FROM_ADDR'),
        'from_name'           => getenv('EMAIL_FROM_NAME'),
        'to_name'             => getenv('EMAIL_TO_NAME'),
        'to_question_address' => getenv('EMAIL_QUESTION_ADDR'),
        'to_contact_address'  => getenv('EMAIL_CONTACT_ADDR'),
    ],
    'database'    => [
        'adapter'  => getenv('DB_ADAPTER'),
        'host'     => getenv('DB_HOST'),
        'username' => getenv('DB_USERNAME'),
        'password' => getenv('DB_PASSWORD'),
        'dbname'   => getenv('DB_DATABASE'),
    ],

    /**
     * Directories
     * These must ALWAYS have a trailing "/"
     */
    'baseUri'        => getenv('BASE_URI'),
    'cacheDir'       => DOCROOT . 'cache/',
    'componentsDir'  => APP_PATH . 'components/',
    'configDir'      => DOCROOT . 'config/',
    'controllersDir' => APP_PATH . 'controllers/',
    'emailsDir'      => DOCROOT . 'emails/',
    'formsDir'       => APP_PATH . 'forms/',
    'libraryDir'     => APP_PATH . 'library/',
    'migrationsDir'  => APP_PATH . 'migrations/',
    'middlewareDir'  => APP_PATH . 'middleware/',
    'modelsDir'      => APP_PATH . 'models/',
    'pluginsDir'     => APP_PATH . 'plugins/',
    'resourcesDir'   => DOCROOT . 'resources/',
    'tasksDir'       => APP_PATH . 'tasks/',
    'testsDir'       => DOCROOT . 'tests/',
    'viewsDir'       => APP_PATH . 'views/',

]);

return $config;

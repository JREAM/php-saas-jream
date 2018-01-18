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

  'title' => getenv('SITE_TITLE'),
  'session_hash' => 'D0__2&$whatLORD$As4Sayy_)s5<E1+WilBeWe1lll2#', // Do not change this
  'cookie_hash' => '#_can$iSAY>let*US*EN~cryp_T-theCookieS!',
  'hashids_hash' => 'Jr34mH4$!-!iD',
  'url_static' => 'https://d2qmoq5vnrtrov.cloudfront.net/',

  'currency' => 'usd',
  'email' => [
    'from_address' => getenv('EMAIL_FROM_ADDR'),
    'from_name' => getenv('EMAIL_FROM_NAME'),
    'to_name' => getenv('EMAIL_TO_NAME'),
    'to_question_address' => getenv('EMAIL_QUESTION_ADDR'),
    'to_contact_address' => getenv('EMAIL_CONTACT_ADDR'),
  ],
  'database' => [
    'adapter' => getenv('DB_ADAPTER'),
    'host' => getenv('DB_HOST'),
    'port' => getenv('DB_PORT'),
    'username' => getenv('DB_USERNAME'),
    'password' => getenv('DB_PASSWORD'),
    'dbname' => getenv('DB_DATABASE'),
  ],
  'memory' => [
    'redis' => [
      'host' => getenv('REDIS_HOST'),
      'port' => getenv('REDIS_PORT'),
      'db' => getenv('REDIS_DB')
    ]
  ],

  /**
   * URL Settings
   * Always strip the trailing slash(es) if exists.
   * baseUri is used by Phalcon (I believe)
   */
  'url' => rtrim(getenv('URL'), '/') . '/',
  'baseUri' => rtrim(getenv('BASE_URI'), '/') . '/',

  /**
   * Directories
   * These must ALWAYS have a trailing "/"
   */
  'application' => [
    'cacheDir' => DOCROOT . 'cache/',
    'componentsDir' => APP_PATH . 'components/',
    'configDir' => APP_PATH . 'config/',
    'controllersDir' => APP_PATH . 'controllers/',
    'formsDir' => APP_PATH . 'forms/',
    'logsDir' => DOCROOT . 'logs/',
    'libraryDir' => APP_PATH . 'library/',
    'migrationsDir' => APP_PATH . 'migrations/',
    'middlewareDir' => APP_PATH . 'middleware/',
    'modelsDir' => APP_PATH . 'models/',
    'pluginsDir' => APP_PATH . 'plugins/',
    'securityDir' => APP_PATH . 'security/',
    'resourcesDir' => DOCROOT . 'resources/',
    'emailsDir' => DOCROOT . 'resources/emails/',
    'tasksDir' => APP_PATH . 'tasks/',
    'testsDir' => DOCROOT . 'tests/',
    'viewsDir' => APP_PATH . 'views/',
  ]
]);



return $config;

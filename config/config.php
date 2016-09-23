<?php

// --------------------------------------------------------------
// Site Config
// --------------------------------------------------------------

$config = new \Phalcon\Config([
    'title' => 'JREAM',
    'hash'  => 'jream', // Do not change this
    'url_static' => 'https://d2qmoq5vnrtrov.cloudfront.net/',
    'email' => [
        'from_address' => 'hello@jream.com',
        'from_name'    => 'JREAM',
        'to_name'      => 'JREAM',
        'to_question_address' => 'imboyus@gmail.com',
        'to_contact_address'  => 'hello@jream.com'
    ],
    'database' => [
        'adapter'  => 'Mysql',
        'host'     => 'localhost',
        'username' => 'root',
        'password' => 'root',
        'dbname'   => 'jream'
    ],
    'application' => [
        'controllersDir' => APP_DIR . 'controllers/',
        'modelsDir'      => APP_DIR . 'models/',
        'viewsDir'       => APP_DIR . 'views/',
        'pluginsDir'     => APP_DIR . 'plugins/',
        'libraryDir'     => APP_DIR . 'library/',
    ]
]);

/**
 * Overwrite the configuration for LIVE version
 */
if (file_exists(__DIR__ . '/config-overwrite.php')) {
    require_once __DIR__ . '/config-overwrite.php';
}


return $config;

// End of File
// --------------------------------------------------------------

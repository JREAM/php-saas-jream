<?php
use \Phalcon\Mvc\View\Engine\Volt as VoltEngine;

// --------------------------------------------------------------
// Services
// --------------------------------------------------------------
$di = new Phalcon\DI\FactoryDefault();

// --------------------------------------------------------------------
// Make the config accessible
// --------------------------------------------------------------------
$di->setShared('config', function() use ($config) {
    return $config;
});

// --------------------------------------------------------------------
// For Api Settings
// --------------------------------------------------------------------
$di->setShared('api', function () use ($api) {
    return $api;
});

// --------------------------------------------------------------
// Apply the Router
// --------------------------------------------------------------
$di->setShared('router', function() {
    return require CONFIG_DIR . '/routes.php';
});

// --------------------------------------------------------------------
// The URL component is used to generate all kind of urls in the application
// --------------------------------------------------------------------
$di->setShared('url', function () use ($config) {
    $url = new Phalcon\Mvc\Url();
    $url->setBaseUri(\BASE_URI);
    return $url;
});

// -----------------------------------
// Custom Dispatcher (Overrides the default)
// (Shared = Singleton)
// -----------------------------------
$di->setShared('dispatcher', function() use ($di) {

    $eventsManager = $di->getShared('eventsManager');
    $eventsManager->attach('dispatch', new Event\Dispatch());
    $eventsManager->attach('dispatch', new Component\Permission());

    // -----------------------------------
    // Return the new dispatcher with the
    // Events Manager Attached
    // -----------------------------------
    $dispatcher = new \Phalcon\Mvc\Dispatcher();
    $dispatcher->setEventsManager($eventsManager);
    return $dispatcher;
});

// -----------------------------------
// Register Component libraries
// -----------------------------------
$di->setShared('component', function() {
    $obj = new stdClass();
    $obj->helper= new \Component\Helper();
    $obj->email = new \Component\Email();
    return $obj;
});

// --------------------------------------------------------------------
// View component
// --------------------------------------------------------------------
$di->setShared('view', function () use ($config) {
    $view = new \Phalcon\Mvc\View();
    $view->setViewsDir($config->application->viewsDir);
    $view->registerEngines([
        '.volt' => function ($view, $di) use ($config) {

            // ------------------------------------------------
            // Volt Template Engine
            // ------------------------------------------------
            $volt = new VoltEngine($view, $di);

            $volt->setOptions([
                'compiledPath' => CACHE_DIR,
                'compiledSeparator' => '_',
                // ------------------------------------------------
                // For DEV, to prevent Caching annoyances
                // ------------------------------------------------
                'compileAlways' => true
            ]);

            // Use Cache for production site
            if (\STAGE == 'production') {
                $voltOptions['compileAlways'] = false;
            }

            return $volt;
        },
        // --------------------------------------------------------------------
        // The Default Templating (However, VOLT is cleaner)
        // --------------------------------------------------------------------
        '.phtml' => '\Phalcon\Mvc\View\Engine\Php'
    ]);

    // Used for global variables (See: events/afterExecuteRoute)
    $view->system = new \stdClass();

    return $view;
});

// --------------------------------------------------------------------
// Database Connection
// --------------------------------------------------------------------
$di->set('db', function () use ($di, $config) {
    $eventsManager = $di->getShared('eventsManager');
    $eventsManager->attach('db', new Event\Database());

    $database = new Phalcon\Db\Adapter\Pdo\Mysql((array) $config->database);
    $database->setEventsManager($eventsManager);

    return $database;
});

// -----------------------------------
// Redis for Caching
// -----------------------------------
$redis = new \Redis();
$redis->connect("localhost", 6379);

// -----------------------------------
// Models Manager
// -----------------------------------
$di->set('modelsManager', function() {
    \Phalcon\Mvc\Model::setup(['ignoreUnknownColumns' => true]);
    return new \Phalcon\Mvc\Model\Manager();
});

// -----------------------------------
// Models Meta-Data
// -----------------------------------
$di->set('modelsMetadata', function() use ($redis) {
    return new \Phalcon\Mvc\Model\MetaData\Redis([
        "lifetime" => 3600,
        "redis"    => $redis
    ]);
});

// -----------------------------------
// ORM And Front-end Caching
// -----------------------------------
$di->set('modelsCache', function() {

    // Cache data for one day by default
    // It's cleared using fabfile for a deploy
    $frontCache = new \Phalcon\Cache\Frontend\Data([
        "lifetime" => 86400
    ]);

    // Redis connection settings
    $cache = new \Phalcon\Cache\Backend\Redis($frontCache, [
        "redis" => $redis
    ]);

    return $cache;
});


// --------------------------------------------------------------------
// Session
// --------------------------------------------------------------------
$di->setShared('session', function () {
    $session = new \Phalcon\Session\Adapter\Files();

    $session->start();
    return $session;
});


// --------------------------------------------------------------------
// For Flashing Data
// --------------------------------------------------------------------
$di->setShared('flash', function() {
    // There is a Direct, and a Session
    $flash = new \Phalcon\Flash\Session([
        'error'     => 'alert alert-danger',
        'success'   => 'alert alert-success',
        'notice'    => 'alert alert-info',
        'warning'   => 'alert alert-warning',
    ]);
    return $flash;
});


// -----------------------------------
// Set the Security for Usage
// -----------------------------------
$di->setShared('security', function(){
    $security = new \Phalcon\Security();
    $security->setWorkFactor(12);
    return $security;
});

// -----------------------------------
// For production error logging
// -----------------------------------
$di->setShared('sentry', function() use ($api) {
    return new \Raven_Client($api->getSentry);
});

// -----------------------------------
// For local error logging
// -----------------------------------
if (\STAGE != 'production') {
    // This is ONLY used locally
    $di->setShared('whoops', function() {
        $whoops = new \Whoops\Run;
        return $whoops;
    });

    $whoops = $di->get('whoops')->register();
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
}

// -----------------------------------
// Mailer In Use
// -----------------------------------
$di->setShared('email', function(array $data) use ($di, $api) {
    $to       = new \SendGrid\Email($data['to_name'], $data['to_email']);
    $from     = new \SendGrid\Email($data['from_name'], $data['from_email']);
    $content  = new \SendGrid\Content("text/html", $data['content']);

    $mail     = new \SendGrid\Mail($from, $data['subject'], $to, $content);

    $sg       = new \SendGrid($api->sendgrid->key);
    $response = $sg->client->mail()->send()->post($mail);

    // Catch a Non 200 Error
    if ( ! in_array($response->_status_code, [200, 201, 202])) {
        $di->get('sentry')->captureMessage(
            sprintf("ErrorCode: %s | Body: %s", $response->_status_code, $response->_body)
        );
    }

    return $response;
});

// -----------------------------------
// Make the api accessible
// -----------------------------------
$di->setShared('facebook', function() use ($api) {
    return new \Facebook\Facebook([
        'app_id'                => $api->fb->appId,
        'app_secret'            => $api->fb->secret,
        'default_graph_version' => 'v2.5'
    ]);
});

$di->setShared('mailchimp', function() use ($api) {
    return new \Mailchimp($api->mailchimp->key);
});

// End of File
// --------------------------------------------------------------------

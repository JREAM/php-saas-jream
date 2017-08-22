<?php

use \Phalcon\Crypt;
use \Phalcon\Http\Response\Cookies;
use Phalcon\Events\Manager as EventsManager;
use \Phalcon\Mvc\View\Engine\Volt as VoltEngine;

/**
 * ==============================================================
 * Services
 * =============================================================
 */
$di = new \Phalcon\DI\FactoryDefault();


$eventsManager = new EventsManager;
$di->setShared('eventsManager', $eventsManager);

/**
 * ==============================================================
 * Set Encryption Token for all Cookies
 * =============================================================
 */
$di->set('crypt', function () use ($config) {
    $crypt = new Crypt();
    $crypt->setKey($config->get('cookie_hash'));
    return $crypt;
});


/**
 * ==============================================================
 * Cookie Encryption is on by default,
 *          this just ensures it for my personal memory.
 * =============================================================
 */
$di->set('cookies', function () {
    $cookies = new Cookies();
    $cookies->useEncryption(true);
    return $cookies;
});


/**
 * ==============================================================
 * Session
 * =============================================================
 */
$di->setShared('session', function () {
    $session = new \Phalcon\Session\Adapter\Files();
    $session->start();
    return $session;
});


/**
 * ==============================================================
 * Session Flash Data
 * =============================================================
 */
$di->setShared('flash', function($mode = 'session') {

    $mode = strtolower(trim($mode));
    $validModes = ['session', 'direct'];
    if ( ! in_array($mode, $validModes ) ) {
        throw new \InvalidArgumentException('Flash Message Error, tried using $mode, must use: ' . explode(',', $validModes));
    }

    // There is a Direct, and a Session
    $flash = new \Phalcon\Flash\Session([
        'error'     => 'alert alert-danger',
        'success'   => 'alert alert-success',
        'notice'    => 'alert alert-info',
        'warning'   => 'alert alert-warning',
    ]);
    return $flash;
});


/**
 * ==============================================================
 * Make Config and api Accessible where we have DI
 * =============================================================
 */
$di->setShared('config', function() use ($config) {
    return $config;
});

$di->setShared('api', function () use ($api) {
    return $api;
});


/**
 * ==============================================================
 * Apply the Router
 * =============================================================
 */
$di->setShared('router', function() use($config) {
    return require $config->get('configDir') . 'routes.php';
});


//$di->setShared('router', function () {
//    // Use the annotations router. We're passing false as we don't want the router to add its default patterns
//    $router = new RouterAnnotations(false);
//
//    // Read the annotations from ProductsController if the URI starts with /api/products
//    $router->addResource("Products", "/api/products");
//
//    return $router;
//});

/**
 * ==============================================================
 * The URL component is used to generate all kind of urls
 *  in the application
 * =============================================================
 */
$di->setShared('url', function () use ($config) {
    $url = new \Phalcon\Mvc\Url();
    $url->setBaseUri($config->get('baseUri'));
    return $url;
});


/**
 * ==============================================================
 * Custom Dispatcher (Overrides the default)
 * =============================================================
 */
$di->setShared('dispatcher', function() use ($di, $eventsManager) {

    $eventsManager->attach('dispatch', new \Components\Permission());
    $eventsManager->attach('dispatch', new \Middleware\Dispatch());

    $dispatcher = new \Phalcon\Mvc\Dispatcher();
    $dispatcher->setEventsManager($eventsManager);

    return $dispatcher;
});


/**
 * ==============================================================
 * Register Component libraries
 * =============================================================
 */
$di->setShared('component', function() {
    $obj = new \stdClass();
    $obj->cookies = new \Components\Cookies();
    $obj->helper  = new \Components\Helper();
    $obj->email   = new \Components\Email();
    return $obj;
});


/**
 * ==============================================================
 * View component
 * =============================================================
 */
$di->setShared('view', function () use ($config, $di) {
    $view = new \Phalcon\Mvc\View();
    $view->setViewsDir($config->get('viewsDir'));
    $view->registerEngines([
        '.volt' => function ($view, $di) use ($config) {

            $path = APPLICATION_ENV == APP_TEST ? DOCROOT . 'tests/_cache/' : $config->get('cacheDir');

            // ------------------------------------------------
            // Volt Template Engine
            // ------------------------------------------------
            $volt = new VoltEngine($view, $di);

            $volt->setOptions([
                'compiledPath' => $path,
                'compiledSeparator' => '_',
                'compileAlways'     => APPLICATION_ENV !== APP_PRODUCTION,
            ]);

            $volt->getCompiler()
                ->addFunction('strtotime', 'strtotime')
                ->addFunction('sprintf', 'sprintf')
                ->addFunction('str_replace', 'str_replace')
                ->addFunction('is_a', 'is_a');

            // Use Cache for live site
            if (\APPLICATION_ENV == \APP_PRODUCTION) {
                $voltOptions['compileAlways'] = false;
            }

            return $volt;
        },
        // --------------------------------------------------------------------
        // The Default Templating (However, VOLT is cleaner)
        // --------------------------------------------------------------------
        '.phtml' => '\Phalcon\Mvc\View\Engine\Php'
    ]);

    // Used for global variables (See: middleware/afterExecuteRoute)
    $view->setVar('version', \Phalcon\Version::get());

    // @TODO: If i wanted i could pass routes in here for JS, anything to JS here?

    return $view;
});


/**
 * ==============================================================
 * Database Connection
 * =============================================================
 */
$di->set('db', function () use ($di, $config, $eventsManager) {
    $eventsManager->attach('db', new \Middleware\Database());

    $database = new Phalcon\Db\Adapter\Pdo\Mysql((array) $config->get('database'));
    $database->setEventsManager($eventsManager);

    return $database;
});


/**
 * ==============================================================
 * Redis (For Caching)
 * =============================================================
 */
$redis = new \Redis();
$redis->connect("localhost", 6379);


/**
 * ==============================================================
 * Model Manager
 * =============================================================
 */
$di->set('modelsManager', function() {
    \Phalcon\Mvc\Model::setup(['ignoreUnknownColumns' => true]);
    return new \Phalcon\Mvc\Model\Manager();
});


/**
 * ==============================================================
 * Model Meta Data (Uses Redis)
 * =============================================================
 */
$di->set('modelsMetadata', function() use ($redis) {
    return new \Phalcon\Mvc\Model\MetaData\Redis([
        "lifetime" => 3600,
        "redis"    => $redis
    ]);
});


/**
 * ==============================================================
 * ORM And Front-end Caching
 * =============================================================
 */
$di->set('modelsCache', function() use ($redis) {

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


/**
 * ==============================================================
 * Set the Security for Usage
 * =============================================================
 */
$di->setShared('security', function(){
    $security = new \Phalcon\Security();
    $security->setWorkFactor(12);
    return $security;
});


/**
 * ==============================================================
 * Sentry Error Logging
 * =============================================================
 */
$di->setShared('sentry', function() use ($api) {
    return (new Raven_Client( getenv('GET_SENTRY') ))->install();
});


/**
 * ==============================================================
 * Local Error Logging
 * =============================================================
 */
if (\APPLICATION_ENV !== \APP_PRODUCTION) {
    // This is ONLY used locally
    $di->setShared('whoops', function() {
        return new \Whoops\Run;
    });

    $whoops = $di->get('whoops')->register();
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
}


/**
 * ==============================================================
 * Email Transport to send Mail
 * =============================================================
 */
$di->setShared('s3', function() {
    return new Aws\S3\S3Client([
        'version' => getenv('AWS_S3_VERSION'),
        'region' => getenv('AWS_S3_REGION'),
        'credentials' => [
            'key' => getenv('AWS_S3_ACCESS_KEY'),
            'secret' => getenv('AWS_S3_ACCESS_SECRET_KEY=')
        ]
    ]);
});

/**
 * ==============================================================
 * Email Transport to send Mail
 * =============================================================
 */
$di->setShared('email', function(array $data) use ($di, $api) {
    $to       = new \SendGrid\Email($data['to_name'], $data['to_email']);
    $from     = new \SendGrid\Email($data['from_name'], $data['from_email']);
    $content  = new \SendGrid\Content("text/html", $data['content']);

    $mail     = new \SendGrid\Mail($from, $data['subject'], $to, $content);

    $sg       = new \SendGrid(getenv('SENDGRID_KEY'));
    $response = $sg->client->mail()->send()->post($mail);

    // Catch a Non 200 Error
    if (!in_array($response->statusCode(), [200, 201, 202])) {
        $di->get('sentry')->captureMessage(
            sprintf("Headers: %s | ErrorCode: %s",
                $response->headers(),
                $response->statusCode()
            )
        );
    }

    return $response;
});


/**
 * ==============================================================
 * API: Facebook
 * =============================================================
 */
$di->setShared('facebook', function () use ($api) {
    return new \Facebook\Facebook([
        'app_id'                => getenv('FACEBOOK_APP_ID'),
        'app_secret'            => getenv('FACEBOOK_APP_SECRET'),
        'default_graph_version' => getenv('FACEBOOK_DEFAULT_GRAPH_VERSION')
    ]);
});


/**
 * ==============================================================
 * API: Google
 * =============================================================
 */
$di->setShared('google_auth', function() use ($api) {

    $middleware = \Google\Auth\ApplicationDefaultCredentials::getMiddleware(
        $api->google->scope
    );

    $stack = \GuzzleHttp\HandlerStack::create();
    $stack->push($middleware);

    return new \GuzzleHttp\Client([
        'handler'   => $stack,
        'base_uri'  => 'https://www.googleapis.com',
        'auth'      => 'google_auth'  // authorize all requests
    ]);

});


/**
 * ==============================================================
 * API: Stripe
 * =============================================================
 */
\Stripe\Stripe::setApiKey( getenv('STRIPE_SECRET') );

/**
 * ==============================================================
 * API: Paypal
 * =============================================================
 */
$di->setShared('paypal', function() {
    // Paypal Express
    // @source  https://omnipay.thephpleague.com/gateways/configuring/
    $paypal = \Omnipay\Omnipay::create('PayPal_Express');
    $paypal->setUsername( getenv('PAYPAL_USERNAME') );
    $paypal->setPassword( getenv('PAYPAL_PASSWORD') );
    $paypal->setSignature( getenv('PAYPAL_SIGNATURE') );

    if ( getenv('PAYPAL_TESTMODE') ) {
        $paypal->setTestMode(true);
    }

    return $paypal;
});



/**
 * ==============================================================
 * API: MailChimp
 * Only Used to Subscribe (Should Change Someday)
 * =============================================================
 */
$di->setShared('mailchimp', function() use ($api) {
    return new \Mailchimp( getenv('MAILCHIMP_KEY') );
});


// Set a default dependency injection container
// to be obtained into static methods
\Phalcon\Di::setDefault($di);
\Phalcon\Di::getDefault();

<?php

use Phalcon\Crypt;
use Phalcon\Http\Response\Cookies;
use Phalcon\Security as Security;
use Phalcon\Flash\Session as Flash;
use Phalcon\Session\Adapter\Files as SessionFiles;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Forms\Manager as FormsManager;
use Phalcon\Db\Adapter\Pdo\Mysql as MySQL;
use Phalcon\Filter;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

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
 * Set the Security for Usage
 *
 * @important This comes before the Session
 * =============================================================
 */
$di->setShared('logger', function () use ($config) {

    $log = new Logger('error_log');
    $log->pushHandler(new StreamHandler(
        $config->get('logsDir') . '/error.log',
        Logger::WARNING
    ));

    return $log;
});


/**
 * ==============================================================
 * Set the Security for Usage
 *
 * @important This comes before the Session
 * =============================================================
 */
$di->setShared('security', function () {
    $security = new Security();
    $security->setWorkFactor(12);

    return $security;
});


/**
 * ==============================================================
 * Set Encryption Token for all Cookies
 * =============================================================
 */
$di->set('crypt', function () use ( $config ) {
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
$di->setShared('session', function () use ( $di ) {
    // Start a new Session for every user.
    $session = new SessionFiles();
    if (!$session->isStarted()) {
        $session->start();
    }

    return $session;
});

/**
 * ==============================================================
 * Session Flash Data
 * =============================================================
 */
$di->setShared('flash', function ( $mode = 'session' ) {

    $mode       = strtolower(trim($mode));
    $validModes = [ 'session', 'direct' ];
    if ( ! in_array($mode, $validModes, true) ) {
        throw new \InvalidArgumentException('Flash Message Error, tried using $mode, must use: ' .
                                            explode(',', $validModes));
    }

    // There is a Direct, and a Session
    $flash = new Flash([
        'error'   => 'alert alert-danger',
        'success' => 'alert alert-success',
        'notice'  => 'alert alert-info',
        'warning' => 'alert alert-warning',
    ]);

    return $flash;
});


/**
 * ==============================================================
 * Make Config/Api Accessible where we have DI
 * =============================================================
 */
$di->setShared('config', function () use ( $config ) {
    return $config;
});

$di->setShared('api', function () use ( $api ) {
    return $api;
});


/**
 * ==============================================================
 * Apply the Router
 * =============================================================
 */
$di->setShared('router', function () use ( $config ) {
    return require $config->get('configDir') . 'routes.php';
});


/**
 * ==============================================================
 * The URL component is used to generate all kind of urls
 *  in the application
 * =============================================================
 */
$di->setShared('url', function () use ( $config ) {
    $url = new \Phalcon\Mvc\Url();
    $url->setBaseUri($config->get('baseUri'));

    return $url;
});


/**
 * ==============================================================
 * Custom Dispatcher (Overrides the default)
 * =============================================================
 */
$di->setShared('dispatcher', function () use ( $di, $eventsManager ) {

    $eventsManager->attach('dispatch', new PermissionPlugin());
    $eventsManager->attach('dispatch', new Middleware\Dispatch());

    $dispatcher = new \Phalcon\Mvc\Dispatcher();
    $dispatcher->setEventsManager($eventsManager);

    return $dispatcher;
});


/**
 * ==============================================================
 * Register Component libraries
 * =============================================================
 */
$di->setShared('component', function () {
    $obj        = new \stdClass();
    $obj->email = new EmailComponent();

    return $obj;
});


/**
 * ==============================================================
 * HashID's (Encode/Decode, primarily for JS resp/req)
 * =============================================================
 */
$di->setShared('hashids', function () use ( $config ) {
    // Passing a unique string makes items unique
    $hashids = new Hashids\Hashids($config->get('hashids_hash'));

    // Sample Usage:
    // encode(1); encode(1, 2, 3), encodeHex('507f1f77bcf86cd799439011')
    // decode(value), decode(hex_value)

    return $hashids;
});


/**
 * ==============================================================
 * View component
 * =============================================================
 */
$di->setShared('view', function () use ( $config, $di ) {
    $view = new \Phalcon\Mvc\View();
    $view->setViewsDir($config->get('viewsDir'));
    $view->registerEngines([
        '.volt'  => function ( $view, $di ) use ( $config ) {

            $path = APPLICATION_ENV == APP_TEST ? DOCROOT . 'tests/_cache/' : $config->get('cacheDir');

            // ------------------------------------------------
            // Volt Template Engine
            // ------------------------------------------------
            $volt = new VoltEngine($view, $di);

            $volt->setOptions([
                'compiledPath'      => $path,
                'compiledSeparator' => '_',
                'compileAlways'     => APPLICATION_ENV !== APP_PRODUCTION,
            ]);

            $volt->getCompiler()
                ->addFunction('strtotime', 'strtotime')
                ->addFunction('sprintf', 'sprintf')
                ->addFunction('str_replace', 'str_replace')
                ->addFunction('is_a', 'is_a')
                ->addFunction('pageid', function ( $str, $expr ) {
                    return str_replace('-page', '', $str);
                })
            ;

            // Use Cache for live site
            if ( \APPLICATION_ENV == \APP_PRODUCTION ) {
                $voltOptions[ 'compileAlways' ] = false;
            }

            return $volt;
        },
        // --------------------------------------------------------------------
        // The Default Templating (However, VOLT is cleaner)
        // --------------------------------------------------------------------
        '.phtml' => '\Phalcon\Mvc\View\Engine\Php',
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
$di->set('db', function () use ( $di, $config, $eventsManager ) {
    $eventsManager->attach('db', new Middleware\Database());

    $database = new MySQL((array) $config->get('database'));
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
$redis->select(10);  // Use Database 10

/**
 * ==============================================================
 * Filters
 * =============================================================
 */
$di->setShared('filter', function () {
    $filter = new Filter();
    $filter->add('slug', function ( $value ) {
        return new Phalcon\Utils\Slug($value);
    });

    return $filter;
});

/**
 * ==============================================================
 * Model Manager
 * =============================================================
 */
$di->set('modelsManager', function () {
    \Phalcon\Mvc\Model::setup([ 'ignoreUnknownColumns' => true ]);

    return new \Phalcon\Mvc\Model\Manager();
});


/**
 * ==============================================================
 * Model Meta Data (Uses Redis)
 * =============================================================
 */
$di->set('modelsMetadata', function () use ( $redis ) {
    return new \Phalcon\Mvc\Model\MetaData\Redis([
        "lifetime" => 3600,
        "redis"    => $redis,
    ]);
});


/**
 * ==============================================================
 * ORM And Front-end Caching
 * =============================================================
 */
$di->set('modelsCache', function () use ( $redis ) {

    // Cache data for one day by default
    // It's cleared using fabfile for a deploy
    $frontCache = new \Phalcon\Cache\Frontend\Data([
        "lifetime" => 86400,
    ]);

    // Redis connection settings
    $cache = new \Phalcon\Cache\Backend\Redis($frontCache, [
        "redis" => $redis,
    ]);

    return $cache;
});


/**
 * ==============================================================
 * PHP Console for Debugging (Requires Chrome Extension)
 * =============================================================
 */
if ( APPLICATION_ENV !== APP_PRODUCTION ) {
    // Register PhpConsole as PC::debug($foo), PC::tag($bar), PC::debug('msg')
    PhpConsole\Helper::register();
}


/**
 * ==============================================================
 * Sentry Error Logging
 * =============================================================
 */
$di->setShared('sentry', function () use ( $api ) {
    return ( new Raven_Client(getenv('GET_SENTRY')) )->install();
});

/**
 * ==============================================================
 * Local Error Logging
 * =============================================================
 */
if ( \APPLICATION_ENV !== \APP_PRODUCTION ) {
    // This is ONLY used locally

    $whoops = new \Whoops\Run();

    // This is so it is accessible in the global Middleware Dispatcher
    $di->setShared('whoops', function () use ( $whoops ) {
        return $whoops;
    });

    // The default page handler
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);

    // Push another handler if it is an AJAX call for JSON responses.
    if ( \Whoops\Util\Misc::isAjaxRequest() ) {
        $jsonHandler = new \Whoops\Handler\JsonResponseHandler();
        $jsonHandler->setJsonApi(true);
        $whoops->pushHandler($jsonHandler);
    }

    $whoops->register();

}


/**
 * ==============================================================
 * Email Transport to send Mail
 * =============================================================
 */
$di->setShared('s3', function () {
    return new Aws\S3\S3Client([
        'version'     => getenv('AWS_S3_VERSION'),
        'region'      => getenv('AWS_S3_REGION'),
        'credentials' => [
            'key'    => getenv('AWS_S3_ACCESS_KEY'),
            'secret' => getenv('AWS_S3_ACCESS_SECRET_KEY='),
        ],
    ]);
});


/**
 * ==============================================================
 * Faker, do NOT put this in the dev-dependencies so accidents don't
 * happen. Regardless, Ill attempt to protect it anyways.
 * =============================================================
 */
$di->set('faker', function () {
    if ( \APPLICATION_ENV !== \APP_PRODUCTION ) {
        return \Faker\Factory::create();
    }

    return false;
});

$di->set('fakerData', function () {
    // Allows me to get data and have it empty if I like with one rule check
    $faker = false;
    if ( \APPLICATION_ENV !== \APP_PRODUCTION ) {
        $faker = \Faker\Factory::create();
    }

    return (object) [
        'NOTE'           => 'This is ALL completely FAKE data for TESTING.',
        'alias'          => $faker ? sprintf('faker%s%s%s', str_replace('.', '', $faker->title), $faker->firstName, $faker->lastName) : null,
        'email'          => $faker ? 'faker_' . $faker->safeEmail : null,
        'password'       => $faker ? $faker->password : null,
        'firstName'      => $faker ? $faker->firstName : null,
        'lastName'       => $faker ? $faker->lastName : null,
        'question'       => $faker ? $faker->sentence(random_int(15, 35)) : null,
        'questionReply'  => $faker ? $faker->sentence(random_int(5, 15)) : null,
        'address'        => $faker ? $faker->streetAddress : null,
        'city'           => $faker ? $faker->city : null,
        'zip'            => $faker ? $faker->postcode : null,
        'country'        => $faker ? $faker->country : null,
        'phone'          => $faker ? $faker->phoneNumber : null,
        'ccType'         => $faker ? $faker->creditCardType : null,
        'ccNumber'       => $faker ? $faker->creditCardNumber : null,
        'ccNumberStripe' => $faker ? '4242424242424242' : null,
        'ccExpMonth'     => $faker ? 12 : null,
        'ccExpYear'      => $faker ? date('Y', strtotime('+2 years')) : null,
    ];
});

/**
 * ==============================================================
 * Email Transport to send Mail
 * =============================================================
 */
$di->setShared('email', function ( array $data ) use ( $di, $api ) {

    // For Debugging
    if ( \APPLICATION_ENV != \APP_PRODUCTION && getenv('DEBUG_EMAIL') ) {
        $transport = ( new Swift_SmtpTransport('localhost', 1025) );
        $mailer    = new Swift_Mailer($transport);

        // Create a message
        $message = ( new Swift_Message($data[ 'subject' ]) )
            ->setFrom([ $data[ 'from_email' ] => $data[ 'from_name' ] ])
            ->setTo([ $data[ 'to_email' ] => $data[ 'to_name' ] ])
            ->setBody($content);

        return $mailer->send($message);
    }

    $to      = new \SendGrid\Email($data[ 'to_name' ], $data[ 'to_email' ]);
    $from    = new \SendGrid\Email($data[ 'from_name' ], $data[ 'from_email' ]);
    $content = new \SendGrid\Content("text/html", $data[ 'content' ]);

    $mail = new \SendGrid\Mail($from, $data[ 'subject' ], $to, $content);

    $sg       = new \SendGrid(getenv('SENDGRID_KEY'));
    $response = $sg->client->mail()->send()->post($mail);

    // Catch a Non 200 Error
    if ( ! in_array($response->statusCode(), [ 200, 201, 202 ], true) ) {
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
$di->setShared('facebook', function () use ( $api ) {
    return new \Facebook\Facebook([
        'app_id'                => getenv('FACEBOOK_APP_ID'),
        'app_secret'            => getenv('FACEBOOK_APP_SECRET'),
        'default_graph_version' => getenv('FACEBOOK_DEFAULT_GRAPH_VERSION'),
    ]);
});


/**
 * ==============================================================
 * API: Google
 * =============================================================
 */
$di->setShared('google', function ($accessToken = false) use ( $api, $config ) {

    $client = new Google_Client();

    // OAuth Keys
    $client->setClientId(getenv('GOOGLE_CLIENT_ID'));
    $client->setClientSecret(getenv('GOOGLE_CLIENT_SECRET'));

    // Simple API Key
    $client->setDeveloperKey(getenv('GOOGLE_DEVELOPER_KEY'));

    $client->setApplicationName('JREAM');

    // Scopes (aka Permissions)
    $client->setScopes((array) $api->google->scopes);
    $client->setIncludeGrantedScopes(true); // Include any previous authorization
    // Optional to pass along an access token when instantiating
    if ($accessToken) {
        $client->setAccessToken($accessToken);
    }

    // The url always has a trailing /
    $redirectUri = "{$config->url}api/auth/google";
    $client->setRedirectUri($redirectUri);
    return $client;
});


/**
 * ==============================================================
 * API: Stripe
 * =============================================================
 */
\Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET'));

/**
 * ==============================================================
 * API: Paypal
 * =============================================================
 */
$di->setShared('paypal', function () {
    // Paypal Express
    // @source  https://omnipay.thephpleague.com/gateways/configuring/
    $paypal = \Omnipay\Omnipay::create('PayPal_Express');
    $paypal->setUsername(getenv('PAYPAL_USERNAME'));
    $paypal->setPassword(getenv('PAYPAL_PASSWORD'));
    $paypal->setSignature(getenv('PAYPAL_SIGNATURE'));

    if ( getenv('PAYPAL_TESTMODE') ) {
        $paypal->setTestMode(true);
    }

    return $paypal;
});


// Set a default dependency injection container
// to be obtained into static methods
\Phalcon\Di::setDefault($di);
\Phalcon\Di::getDefault();

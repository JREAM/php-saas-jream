<?php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
 * ==============================================================
 * Set the Security for Usage
 *
 * @important This comes before the Session
 * =============================================================
 */
$di->setShared('logger', function () use ($config) {

    $log = new Logger('error_log');
    $log->pushHandler(new StreamHandler($config->get('logsDir') . '/error.log', Logger::WARNING));

    return $log;
});

/**
 * ==============================================================
 * Sentry Error Logging
 * =============================================================
 */
$di->setShared('sentry', function () use ($api) {
    return (new Raven_Client(getenv('GET_SENTRY')))->install();
});

/**
 * ==============================================================
 * Local Error Logging
 * =============================================================
 */
if (\APPLICATION_ENV !== \APP_PRODUCTION) {
    // This is ONLY used locally

    $whoops = new \Whoops\Run();

    // This is so it is accessible in the global Middleware Dispatcher
    $di->setShared('whoops', function () use ($whoops) {
        return $whoops;
    });

    // The default page handler
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);

    // Push another handler if it is an AJAX call for JSON responses.
    if (\Whoops\Util\Misc::isAjaxRequest()) {
        $jsonHandler = new \Whoops\Handler\JsonResponseHandler();
        $jsonHandler->setJsonApi(true);
        $whoops->pushHandler($jsonHandler);
    }

    $whoops->register();
}

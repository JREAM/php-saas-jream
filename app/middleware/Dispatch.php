<?php

namespace Middleware;

use Phalcon\Events\Event;
use Phalcon\Http\Response;
use Phalcon\Mvc\Dispatcher;
use Phalcon\DI\FactoryDefault;
use Phalcon\Exception as PhalconException;

class Dispatch
{

    public function __construct()
    {
        $this->di = FactoryDefault::getDefault();
    }

    // -----------------------------------------------------------------------------

    /**
     * Handle Any jobs before a route is executed
     *
     * @param Event $dispatcher
     *
     * @return Event
     */
    public function beforeExecuteRoute(Event $dispatcher): Event
    {
        // -----------------------------------------------------------------------------
        // Handle Session/Form Data
        // @TODO Convert this to $this->>session probably?
        // -----------------------------------------------------------------------------
        if ( ! isset($_SESSION)) {
            return $dispatcher;
        }

        // Clear the form data once the page reloads and it's viewable
        if (isset($_SESSION[ 'formDataSeen' ]) && $_SESSION[ 'formDataSeen' ] >= 1) {
            $_SESSION[ 'formData' ]     = null;
            $_SESSION[ 'formDataSeen' ] = null;
        }

        if ( ! empty($_POST)) {
            $postData = [];
            foreach ($_POST as $key => $value) {
                $key              = strip_tags($key);
                $value            = strip_tags($value);
                $postData[ $key ] = $value;
            }
            // Store the Session Data
            $_SESSION[ 'formData' ]     = $postData;
            $_SESSION[ 'formDataSeen' ] = -1;
        }

        if (isset($_SESSION[ 'formDataSeen' ])) {
            // Increments to 0 (false)
            // Once loaded again, increments to 1 (true)
            //      & Removed on next page load.
            ++$_SESSION[ 'formDataSeen' ];
        }

        return $dispatcher;
    }

    // -----------------------------------------------------------------------------

    /**
     *
     * @param Event $dispatcher
     *
     * @return Event
     */
    public function afterExecuteRoute(Event $dispatcher): Event
    {
        return $dispatcher;
    }

    // -----------------------------------------------------------------------------

    /**
     * Handle Exceptions Locally and LIve
     *
     * @param  Event                                      $event
     * @param  Dispatcher                                 $dispatcher
     * @param  PhalconException|WhoopsException|Exception $exception
     *
     * @return bool
     */
    public function beforeException(Event $event, Dispatcher $dispatcher, $exception): bool
    {
        error_log($exception->getMessage(), 0);

        if (\APPLICATION_ENV === \APP_PRODUCTION) {
            // GetSentry to log the error
            $this->di->get('sentry')->captureException($exception);
        } else {
            $whoops = $this->di->get('whoops')->register();
            $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
            $whoops->handleException($exception);
            $whoops->register();
        }

        // -----------------------------------
        // Handle 404 exceptions
        // -----------------------------------
        if ($exception instanceof \Phalcon\Mvc\Dispatcher\Exception) {
            $dispatcher->forward([
                'controller' => 'index',
                'action'     => 'show404',
            ]);

            return false;
        }

        // -----------------------------------
        // Handle other exceptions
        // -----------------------------------
        $dispatcher->forward([
            'controller' => 'index',
            'action'     => 'show503',
        ]);

        return false;
    }

    // -----------------------------------------------------------------------------

}

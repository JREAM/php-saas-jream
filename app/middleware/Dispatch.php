<?php

namespace Middleware;

use Phalcon\Events\Event;
use Phalcon\Http\Request;
use Library\TokenManager;
use Phalcon\DI\FactoryDefault;

class Dispatch
{
//    protected $request;

    public function __construct()
    {
        $this->di = FactoryDefault::getDefault();
//        $this->request = new Request();
    }

    // -------------------------------------------------------------

    /**
     * Handle Any jobs before a route is executed
     *
     * @param  \Phalcon\Events\Event $dispatcher
     *
     * @return void
     */
    public function beforeExecuteRoute(Event $dispatcher)
    {

        // --------------------------------------------------------------
        // Session CSRF
        // 1: Create a user-session CSRF Token Pair if one does NOT exist.
        // .. All Users signed in or not must have a CSRF token.
        // --------------------------------------------------------------
        $tokenManager = new TokenManager();
        if (!$tokenManager->hasToken()) {
            $tokenManager->generate();
        }

        // --------------------------------------------------------------
        // Handle Session/Form Data
        // @TODO Convert this to $this->>session probably?
        // --------------------------------------------------------------
        if (!isset($_SESSION)) {
            return;
        }

        // Clear the form data once the page reloads and it's viewable
        if (isset($_SESSION['formDataSeen']) && $_SESSION['formDataSeen'] >= 1) {
            $_SESSION['formData'] = null;
            $_SESSION['formDataSeen'] = null;
        }

        if (!empty($_POST)) {
            $postData = [];
            foreach ($_POST as $key => $value) {
                $key = strip_tags($key);
                $value = strip_tags($value);
                $postData[$key] = $value;
            }
            // Store the Session Data
            $_SESSION['formData'] = $postData;
            $_SESSION['formDataSeen'] = -1;
        }

        if (isset($_SESSION['formDataSeen'])) {
            // Increments to 0 (false)
            // Once loaded again, increments to 1 (true)
            //      & Removed on next page load.
            ++$_SESSION['formDataSeen'];
        }
    }

    // -------------------------------------------------------------

    public function afterExecuteRoute($dispatcher)
    {
    }

    // -------------------------------------------------------------

    /**
     * Handle Exceptions Locally and LIve
     *
     * @param  object $event
     * @param  object $dispatcher
     * @param  object $exception
     *
     * @return bool
     */
    public function beforeException($event, $dispatcher, $exception)
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

    // -------------------------------------------------------------
}

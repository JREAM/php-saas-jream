<?php

namespace Middleware;

use Phalcon\Events\Event;

class Ajax
{

    public function __construct()
    {
        $this->di = \Phalcon\DI\FactoryDefault::getDefault();
        $this->config = $this->di->get('config');
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
//        @TODO I need the DI or something to get APP/Request, whatever..
//        $csrf_token = $app->request->getHeader('X-CSRF-TOKEN');
//        $tokenValue= explode(',', $csrf_token);
//        $tokenKey = array_splice($tokenValue, -1);
//
//        $this->di->security->checkToken($tokenKey, $tokenValue)
    }

    /**
     * Calls the middleware
     *
     * @returns bool
     */
    public function call()
    {
        return true;
    }

}

// End of File
// --------------------------------------------------------------

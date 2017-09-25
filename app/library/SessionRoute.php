<?php

namespace Library;

//use Phalcon\Di;
use Phalcon\Di\Injectable;
use Phalcon\Session\Bag as SessionBag;

class SessionRoute extends Injectable
{
    /**
     * @var \Phalcon\Di Dependency Injector
     *                  Must have set Di::setDefault($di) in services (or someplace)
     *                  Otherwise it is just passed in below.
     */
    protected $di;

    /**
     * @var $router \Phalcon\Mvc\Router
     *              from the DI instance
     */
    protected $router;

    /**
     * @var \Phalcon\Session $session
     */
    protected $session;

    /**
     * @var stdClass $p Previous Route
     */
    protected $p;

    /**
     * @var stdClass $c Current Route
     */
    protected $c;

    // -----------------------------------------------------------------------------

    /**
     * SessionRoute constructor.
     *
     * @param null|\Phalcon\Di $di Optionally pass DI, or we resort to DI::getDefault()
     */
    public function __construct()
    {
        $this->session = $this->di->get('session');

        //$this->di     = $di ?: Di::getDefault();
        //$this->router = $this->di->router;
    }

    // -----------------------------------------------------------------------------

    public function getCurrent()
    {
        // Yes, load the previous page
        $c            = new \stdClass();
        $c->iteration = 0;

        $c->controller = (string) $this->router->getControllerName();
        $c->action     = (string) $this->router->getActionName();
        $c->params     = (array) $this->router->getParams();
        $c->paramsStr  = implode('/', $c->params);
        $c->full       = \Library\Url::makeFrom($c->controller, $c->action, $c->params);

        // Track previous page,
        // @TIP Session bag available in DI: $this->persistent->route;
        if ($this->session->isStarted()) {
            $route = new SessionBag('route');

            // Create the current Routes
            foreach ($c as $key => $value) {
                $route->{$key} = $value;
            }

            if ($route->has('iteration')) {
                ++$route->increment;

                if ($route->controller !== $this->router->getControllerName()) {
                    $route->controller = $this->router->getControllerName();
                }
                if ($route->action !== $this->router->getActionName()) {
                    $route->action = $this->router->getActionName();
                }
                if ($route->params !== $this->router->getParams()) {
                    $route->params = $this->router->getParams();
                }
                $currentFullUrl = \Library\Url::makeFrom($route->controller, $route->action, $route->params);
                $route->full    = ($route->full !== $currentFullUrl) ?: $fullUrl;
            }

            $route->controller = $c->controller;
            $route->action     = $c->ac;
            $route->params     = (array) $this->router->getParams();
            $route->full       = \Library\Url::makeFrom($route->controller, $route->action, $route->params);
            $route->increment  = 0;
        }

    }

    // -----------------------------------------------------------------------------

    /**
     *
     */
    public function getPrev()
    {

    }

    // -----------------------------------------------------------------------------

    /**
     *
     */
    public function getPrevious()
    {
        return $this->getPrev();
    }

}

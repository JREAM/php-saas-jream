<?php

namespace Library;

use Phalcon\Di\Injectable;
use Phalcon\Session\Bag as SessionBag;

class SessionRoute extends Injectable
{
    /**
     * @var \Phalcon\Session\Bag
     */
    protected $routeBag;

    /**
     * @var stdClass $prev Previous Route
     */
    protected $prev;

    /**
     * @var stdClass $current Current Route
     */
    protected $current;

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * SessionRoute constructor.
     */
    public function __construct()
    {
        $this->routeBag = new SessionBag('routeBag');
        // Always set current every-time the page is loaded

        // This will set the previous
        $this->setCurrent();
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * Get the current route
     */
    protected function setCurrent(): void
    {

        ++$this->current->iteration;

        // Set the Previous when the increments another time (page load)
        if ($this->current->iteration % 2 === 0) {
            $this->setPrev();
        }
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * @return \stdClass
     */
    public function getCurrent(): \stdClass
    {
        return $this->current;
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * Gets the last route called previously
     * Session bag available in DI: $this->persistent->route;
     *
     * @return \stdClass
     */
    protected function setPrev(): \stdClass
    {
        $this->prev = clone $this->current;

        foreach ($this->prev as $key => $value) {
            if ($key === 'params') {
                $this->prev->{$key} = [];
                continue;
            }
            $this->prev->{$key} = null;
        }
        unset($this->current->iteration);

        // Update anything that's different
        if ($this->prev->controller !== $this->router->getControllerName()) {
            $this->prev->controller = $this->router->getControllerName();
        }

        if ($this->prev->action !== $this->router->getActionName()) {
            $this->prev->action = $this->router->getActionName();
        }

        if ($this->prev->params !== $this->router->getParams()) {
            $this->prev->params = $this->router->getParams();
        }

        $fullUrl = \Library\Url::makeFrom(
            $this->prev->controller,
            $this->prev->action,
            $this->prev->params
        );

        $this->prev->full = ($this->prev->full !== $fullUrl) ?: $fullUrl;

        return $this->prev;
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * @return \stdClass
     */
    public function getPrev(): \stdClass
    {
        return $this->prev;
    }
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * Alias for getPrev
     */
    public function getPrevious()
    {
        return $this->getPrev();
    }
}

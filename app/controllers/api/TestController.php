<?php

namespace Api;

use \User;
use \Promotion;

class TestController extends ApiController
{

    /**
     * @return void
     */
    public function onConstruct()
    {
        parent::initialize();
    }

    // --------------------------------------------------------------

    public function indexAction()
    {
        return $this->output(1, 'This works, maybe?');
    }
}

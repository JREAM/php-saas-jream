<?php

namespace Api\V1;

use \User;
use \Promotion;

class TestController extends ApiBaseController
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

// End of File
// --------------------------------------------------------------

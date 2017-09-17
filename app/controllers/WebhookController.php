<?php
declare(strict_types=1);

namespace Controllers;
use Phalcon\Mvc\View;
use Phalcon\Http\Response;

use Phalcon\Tag;

class WebookController extends BaseController
{

    /**
     * @return void
     */
    public function onConstruct() : void
    {
        parent::initialize();
        Tag::setTitle('Webhook');
    }

    // -----------------------------------------------------------------------------

    /**
     * @return void
     */
    public function indexAction() : void
    {
      echo "Webhook";
    }

    public function awsSQSAction() {
      // Hit by SQS and then updates DB.
    }

}

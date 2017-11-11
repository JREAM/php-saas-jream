<?php

declare(strict_types=1);

namespace Controllers;

use Phalcon\Mvc\View;
use Phalcon\Http\Response;

use Phalcon\Tag;

class WebhookController extends BaseController
{

    /**
     * @return void
     */
    public function onConstruct(): void
    {
        parent::initialize();
        Tag::setTitle('Webhook');
    }

    // -----------------------------------------------------------------------------

    /**
     * @return View
     */
    public function unsubscribeAction()
    {
        //return $this->view->pick('newsletter/unsubscribe');
    }

    // -----------------------------------------------------------------------------

    /**
     * @return void
     */
    public function indexAction(): void
    {
        echo "Webhook";
    }

    public function SQSAction()
    {
        // Hit by SQS and then updates DB.
        echo "SQS";
    }
}

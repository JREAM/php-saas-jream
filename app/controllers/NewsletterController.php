<?php
declare(strict_types=1);

namespace Controllers;

use \Phalcon\Tag;

/**
 * @RoutePrefix("/newsletter")
 */
class NewsletterController extends BaseController
{
    /**
     * @return void
     */
    public function onConstruct()
    {
        parent::initialize();
        Tag::setTitle('Newsletter | ' . $this->di['config']['title']);
    }

    // -----------------------------------------------------------------------------

    /**
     * @return void
     */
    public function indexAction()
    {
        $this->view->setVars([
            'form'     => new \Forms\NewsletterForm(),
        ]);

        $this->view->pick('newsletter/index');
    }

    // -----------------------------------------------------------------------------

    /**
     * @return void
     */
    public function unsubscribeAction()
    {
        $this->view->pick('newsletter/unsubscribe');
    }

}

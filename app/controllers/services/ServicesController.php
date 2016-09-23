<?php
namespace Services;
use \Phalcon\Tag;

class ServicesController extends \BaseController
{

    /**
     * @return void
     */
    public function onConstruct()
    {
        parent::initialize();
    }

    // --------------------------------------------------------------

    /**
     * @return void
     */
    public function indexAction()
    {
        Tag::setTitle('Services | ' . $this->di['config']['title']);

        $this->view->setVars([
            'form' => new \ContactForm(),

            // CSRF
            'tokenKey' => $this->security->getTokenKey(),
            'token' => $this->security->getToken()
        ]);

        $this->view->pick('services/development');
    }

    // --------------------------------------------------------------

}
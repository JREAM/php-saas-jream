<?php

use \Phalcon\Tag;

class ContactController extends \BaseController
{

    const CONTACT_REDIRECT_FAILURE = 'contact';
    const CONTACT_REDIRECT_SUCCESS = 'contact/thanks';

    /**
     * @return void
     */
    public function onConstruct()
    {
        parent::initialize();
        Tag::setTitle('Contact | ' . $this->di['config']['title']);
    }

    // --------------------------------------------------------------

    /**
     * @return void
     */
    public function indexAction()
    {
        $this->view->setVars([
            'form'     => new \ContactForm(),
            'tokenKey' => $this->security->getTokenKey(),
            'token'    => $this->security->getToken(),
        ]);

        $this->view->pick('contact/contact');
    }

    // --------------------------------------------------------------

    /**
     * @return void
     */
    public function thanksAction()
    {
        Tag::setTitle('Contact Email Sent | ' . $this->di['config']['title']);
        $this->view->pick('contact/thanks');
    }

    // --------------------------------------------------------------
}

// End of File
// --------------------------------------------------------------

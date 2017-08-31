<?php
declare(strict_types=1);

namespace Controllers;

use \Phalcon\Tag;

/**
 * @RoutePrefix("/contact")
 */
class ContactController extends BaseController
{

    const CONTACT_REDIRECT_FAILURE = 'contact';
    const CONTACT_REDIRECT_SUCCESS = 'contact/thanks';

    /**
     * @return void
     */
    public function onConstruct() : void
    {
        parent::initialize();
        Tag::setTitle('Contact | ' . $this->di['config']['title']);
    }

    /**
     * @return void
     */
    public function indexAction() : void
    {
        $this->view->setVars([
            'form'     => new \Forms\ContactForm(),
        ]);

        $this->view->pick('contact/contact');
    }

    /**
     * @return void
     */
    public function thanksAction() : void
    {
        Tag::setTitle('Contact Email Sent | ' . $this->di['config']['title']);
        $this->view->pick('contact/thanks');
    }

}

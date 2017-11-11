<?php

declare(strict_types=1);

namespace Controllers;

use Phalcon\Tag;
use Phalcon\Mvc\View;
use Phalcon\Http\Response;

/**
 * @RoutePrefix("/contact")
 */
class ContactController extends BaseController
{

    const CONTACT_REDIRECT_FAILURE = 'contact';
    const CONTACT_REDIRECT_SUCCESS = 'contact/thanks';

    // -----------------------------------------------------------------------------

    /**
     * @return void
     */
    public function onConstruct(): void
    {
        parent::initialize();
        Tag::setTitle('Contact | ' . $this->di[ 'config' ][ 'title' ]);
    }

    // -----------------------------------------------------------------------------

    /**
     * @return View
     */
    public function indexAction(): View
    {
        $this->view->setVars([
            'form' => new \Forms\ContactForm(),
        ]);

        return $this->view->pick('contact/contact');
    }

    // -----------------------------------------------------------------------------

    /**
     * @return View
     */
    public function thanksAction(): View
    {
        Tag::setTitle('Contact Email Sent | ' . $this->di[ 'config' ][ 'title' ]);

        return $this->view->pick('contact/thanks');
    }
}

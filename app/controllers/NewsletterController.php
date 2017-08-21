<?php

namespace Controllers;

use \Phalcon\Tag;

/**
 * @RoutePrefix("/newsletter")
 */
class NewsletterController extends BaseController
{

    // Flash Messages
    const SUBSCRIBE_REDIRECT_SUCCESS = 'newsletter/subscribe';
    const SUBSCRIBE_REDIRECT_FAILURE = 'newsletter/subscribe';

    /**
     * @return void
     */
    public function onConstruct()
    {
        parent::initialize();
        Tag::setTitle('Newsletter | ' . $this->di['config']['title']);
    }

    // --------------------------------------------------------------

    /**
     * @Get(
     *     "/"
     * )
     *
     * @return void
     */
    public function indexAction()
    {
        $this->view->setVars([
            'form'     => new \NewsletterForm(),
            'tokenKey' => $this->security->getTokenKey(),
            'token'    => $this->security->getToken(),
        ]);

        $this->view->pick('newsletter/index');
    }

    // --------------------------------------------------------------

    /**
     * @Post(
     *     "/"
     * )
     * @return void
     */
    public function doSubscribeAction()
    {

    }

    // --------------------------------------------------------------

    /**
     * @return void
     */
    public function doVerifyAction($token)
    {
        $newsletterSubscription = \NewsletterSubscription::findFirstByVerifyKey($token);

        // If Key Not Found
        if (!$newsletterSubscription) {
            $this->view->setVars([
                'result' => 'Oops! Your verification token could not be found! Are you sure you registered for the newsletter?'
            ]);
        } else {
            $newsletterSubscription->verified = 1;
            $newsletterSubscription->updated_at = getDateTime();
        }

        $this->view->pick('newsletter/subscribe-verify');
    }

    // --------------------------------------------------------------

    /**
     * @return void
     */
    public function unsubscribeAction()
    {
        $this->view->pick('newsletter/unsubscribe');
    }

    // --------------------------------------------------------------
}

<?php
declare(strict_types=1);

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

    /**
     * @return void
     */
    public function indexAction()
    {
        $this->view->setVars([
            'form'     => new \NewsletterForm(),
        ]);

        $this->view->pick('newsletter/index');
    }

    /**
     * Verifies a users email address.
     *
     * @param string    $token
     *
     * @return void
     */
    public function doVerifyAction(string $token)
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

    /**
     * @return void
     */
    public function unsubscribeAction()
    {
        $this->view->pick('newsletter/unsubscribe');
    }

}

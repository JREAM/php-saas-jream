<?php

use \Phalcon\Tag;

class NewsletterController extends \BaseController
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
        Tag::setTitle('Newsletter');
    }

    // --------------------------------------------------------------

    /**
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
     * @return void
     */
    public function doSubscribeAction()
    {
        if (!$this->request->isPost()) {
            $this->output(0, "Oh that doesn't work, You must post the form!");
        }

        //Check Recaptcha @TODO
        $email = $this->request->getPost('email');

        // Insert into DB
        $newsletter = new \Newsletter();
        $newsletter->email = $email;
        $newsletter->subscribed = 1;

        // Create a simple hash token
        $newsletter->token = hash('512', $email . mt_rand(1, 2500));
        $newsletter->created_at = getDateTime();
        $newsletter->updated_at = getDateTime();

        // See if this user is registered,
        // Update their account if so!
        $user = \User::findFirst(["
            email = :email:
            OR facebook_email = :email:
            OR google_email = :email:",
            "bind" => [
                "email" => $email
            ]
        ]);

        // Update the User ID
        if ($user) {
            $newsletter->user_id = $user->id;
        }

        // Save the Newsletter
        $result = $newsletter->save();

        // @TODO: Need to return JSON i think
        if (!$result) {
            // Log this error to see what happened
            $this->di->get('sentry')->captureMessage($newsletter->getMessage(), [
                'email' => $email,
            ]);

            $this->output(0, "Something went wrong. There was an error saving, the error has been traced and will be look into.");
            $this->redirect(self::SUBSCRIBE_REDIRECT_FAILURE);
        }

        $this->output(1, "Thank you! You've been subscribed to the JREAM Newsletter.
            You'll receive an email for a double opt-in confirmation. You may un-subscribe at anytime
            at the footer of every email with one click.
        ");
    }

    // --------------------------------------------------------------

    /**
     * @return void
     */
    public function doVerifyAction($token)
    {
        $newsletter = Newsletter::findFirstByVerifyKey($token);

        // If Key Not Found
        if ( ! $newletter ) {
            $this->view->setVars([
                'result' => 'Oops! Your verification token could not be found! Are you sure you registered for the newsletter?'
            ]);
        }
        else {
            $newletter->verified = 1;
            $newletter->updated_at =
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

// End of File
// --------------------------------------------------------------

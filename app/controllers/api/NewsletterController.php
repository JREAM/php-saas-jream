<?php

declare(strict_types=1);

namespace Controllers\Api;

use Phalcon\Http\Response;
use User;
use NewsletterSubscription;

class NewsletterController extends ApiController
{

    public function onConstruct()
    {
        parent::initialize();
    }

    // -----------------------------------------------------------------------------

    /**
     * @return Response
     */
    public function subscribeAction(): Response
    {
        if ( ! $this->request->isPost()) {
            $this->output(0, "Oh that doesn't work, You must post the form!");
        }

        // If Recaptcha fails, Warn and use JS to reload.
        if ( ! new Recaptcha($this->session, $this->request->getPost('g-recaptcha-response'))) {
            // Retrigger: grecaptcha.reset() in JS
            return $this->output(0, 'Recaptcha is invalid, please try again.');
        }

        //Check Recaptcha @TODO
        $email = $this->request->getPost('email');

        // Insert into DB
        $newsletter             = new \Newsletter();
        $newsletter->email      = $email;
        $newsletter->subscribed = 1;

        // Create a simple hash token
        $newsletter->token      = $this->security->hash($email . random_int(1, 2500));
        $newsletter->created_at = getDateTime();
        $newsletter->updated_at = getDateTime();

        // See if this user is registered,
        // Update their account if so!
        $user = \User::findFirst([
            "
            email = :email:
            OR facebook_email = :email:
            OR google_email = :email:",
            "bind" => [
                "email" => $email,
            ],
        ]);

        // Update the User ID
        if ($user) {
            $newsletter->user_id = $user->id;
        }

        // Save the Newsletter
        $result = $newsletter->save();

        // @TODO: Need to return JSON i think
        if ( ! $result) {
            // Log this error to see what happened
            $this->di->get('sentry')->captureMessage($newsletter->getMessage(), [
                'email' => $email,
            ]);

            return $this->output(0, "Something went wrong. There was an error saving, the error has been traced and will be look into.");
        }

        return $this->output(1, "Thank you! You've been subscribed to the JREAM Newsletter.
            You'll receive an email for a double opt-in confirmation. You may un-subscribe at anytime
            at the footer of every email with one click.
        ");
    }

    // -----------------------------------------------------------------------------

    /**
     * @return Response
     */
    public function verifyAction(): Response
    {
        $token      = $this->input->getPost('token');
        $newsletter = NewsletterSubscription::findFirstByToken($token);
        if ( ! $newsletter) {
            return $this->output(0, 'Token not found.');
        }
        $newsletter->is_verified   = 1;
        $newsletter->is_subscribed = 1;
        $newsletter->save();

        return $this->output(1, 'Verified.');
    }

    // -----------------------------------------------------------------------------

    /**
     * @return Response
     */
    public function unsubscribeAction(): Response
    {
        $email      = $this->input->getPost('email');
        $newsletter = NewsletterSubscription::findFirstByEmail($email);
        if ( ! $newsletter) {
            return $this->output(0, 'Subscription not found.');
        }
        $newsletter->is_subscribed = 0;
        $newsletter->save();

        if ($newsletter->user_id) {
            $this->updateUserRow($newsletter->user_id, 0);
        }

        return $this->output(1, 'Unsubscribed');
    }

    // -----------------------------------------------------------------------------

    /**
     * @param integer $user_id
     * @param integer $value
     *
     * @return bool
     */
    protected function updateUserRow(integer $user_id, integer $value): bool
    {
        $user = User::findFirstById($user_id);
        if ($user) {
            $user->newsletter_subscribed = $value;
            $user->save();

            return true;
        }

        return false;
    }

}

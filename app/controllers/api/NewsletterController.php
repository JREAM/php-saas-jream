<?php

namespace Api;

use \User;
use \NewsletterSubscriptions;

/**
 * @RoutePrefix("/api/newsletter")
 */
class NewsletterController extends ApiController
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
     * @return string JSON
     */
    public function subscribeAction()
    {
        $email = $this->input->getPost('email');
        $newsletter = NewsletterSubscriptions::findFirstByEmail($email);

        // Exists, check user table
        if ($newsletter) {
            if ($newsleter->user_id) {
                $this->updateUserRow($newsletter->user_id, 1);
            }
        }

        $newsletter = new NewsletterSubscriptions();
        $newsletter->email = $email;
        $newsletter->is_subscribed = 1;
        $newsletter->save();

        // Exists, check user table
        // if ($newsletter) {
        //     if ($newsleter->user_id) {
        //        $this->updateUserRow($newsletter->user_id, 1);
        //     }
        // }
    }

    /**
     * @return string JSON
     */
    public function unsubscribeAction()
    {
        $email = $this->input->getPost('email');
        $newsletter = NewsletterSubscriptions::findFirstByEmail($email);
        if (!$newsletter) {
            return $this->output(0, 'Subscription not found.');
        }
        $newsletter->is_subscribed = 0;
        $newsletter->save();

        if ($newsleter->user_id) {
            $this->updateUserRow($newsletter->user_id, 0);
        }

        return $this->output(1, 'Unsubscribed');
    }

    /**
     * @return string JSON
     */
    public function verifyAction()
    {
        $token = $this->input->getPost('token');
        $newsletter = NewsletterSubscriptions::findFirstByToken($token);
        if (!$newsletter) {
            return $this->output(0, 'Token not found.');
        }
        $newsletter->is_verified = 1;
        $newsletter->is_subscribed = 1;
        $newsletter->save();

        return $this->output(1, 'Verified.');
    }

    /**
     * @return bool
     */
    protected function updateUserRow($user_id, $boolean)
    {
        $user = User::findFirstById($user_id);
        if ($user) {
            $user->newsletter_subscribed = $boolean;
            $user->save();
            return true;
        }
        return false;
    }
}

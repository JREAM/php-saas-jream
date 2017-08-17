<?php

namespace Api\V1;

use \User;
use \NewsletterSubscribe;

class AuthController extends ApiBaseController
{

    /**
     * @return void
     */
    public function onConstruct()
    {
        parent::initialize();
    }

    // --------------------------------------------------------------

    public function subscribeAction()
    {
        $email = $this->input->getPost('email');
        $newsletter = NewsletterSubscribe::findFirstByEmail($email);

        // Exists, check user table
        if ($newsletter) {
            if ($newsleter->user_id) {
               $this->updateUserRow($newsletter->user_id, 1);
            }
        }

        $newsletter = new NewsletterSubscribe();
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

    public function unsubscribeAction()
    {
        $email = $this->input->getPost('email');
        $newsletter = NewsletterSubscribe::findFirstByEmail($email);
        if (!$newsletter) {
            return false;
        }
        $newsletter->is_subscribed = 0;
        $newsletter->save();

        if ($newsleter->user_id) {
            $this->updateUserRow($newsletter->user_id, 0);
        }

        return true;
    }

    public function verifyAction()
    {
        $token = $this->input->getPost('token');
        $newsletter = NewsletterSubscribe::findFirstByToken($token);
        if (!$newsletter) {
            return false;
        }
        $newsletter->is_verified = 1;
        $newsletter->is_subscribed = 1;
        $newsletter->save();
        return true;
    }

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

// End of File
// --------------------------------------------------------------

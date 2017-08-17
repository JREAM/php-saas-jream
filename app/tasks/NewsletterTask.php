<?php

use Phalcon\Cli\Task;

class NewsletterTask extends Task
{
    public function mainAction()
    {
        echo 'This can be used to send emails to all users';
    }

    public function createListAction()
    {
        $users = \User::findBy("
            is_deleted = :deleted:
            is_banned = :banned:
        ", [
            "deleted" => 0,
            "banned" => 0
        ]);

        if (!$users) {
            echo 'No users found.';
            return false;
        }

        foreach ($users as $user) {
            $newsletter = \NewsletterSubscriptions::findByEmail($user->getEmail());
            if (!$newsletter) {
                printf("Inserting %s into newsletter_subscription.", $user->getEmail);
                $newsletter = new \NewsletterSubscriptions();
                $newsletter->is_subscribed = 1;
                $newsletter->user_id = 1;
                $newsletter->email = $user->getEmail();
                $newsletter->save();
            }
        }
    }

    public function sendEmailAction()
    {
        // Load AWS SES.. only send to ppl that didnt receive email id X
        $subscribers = \NewsletterSubscriptions::get();
    }

}

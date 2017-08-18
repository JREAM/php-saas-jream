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
        $users = \User::find("is_deleted = 0 AND is_banned = 0");

        if (!$users) {
            echo 'No users found.';
            return false;
        }

        foreach ($users as $user) {
            print_r($user->toArray());
            die;
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

    // @TODO: This is a loop, email everybody..
    // @TODO: Read template from database, update database everytim eemail sent so it doesnt double send.
    public function sendEmailAction()
    {
        // Load AWS SES.. only send to ppl that didnt receive email id X
//        $subscribers = \NewsletterSubscriptions::get();

        // Create the Transport
        $transport = Swift_SmtpTransport::newInstance(
                getenv('AWS_SES_HOST'),
                getenv('AWS_SES_PORT'),
                'tls'
            )
            ->setUsername(getenv('AWS_SES_USERNAME'))
            ->setPassword(getenv('AWS_SES_PASSWORD'));

        // Create the Mailer using your created Transport
        $mailer = Swift_Mailer::newInstance($transport);

        // Create a message
        $message = Swift_Message::newInstance('Wonderful Subject')
            ->setFrom([getenv('EMAIL_FROM_ADDR')])
//            ->setTo(['example@example.org' => 'John Doe'])
            ->setTo(['imboyus@gmail.com' => 'Jesseeeee'])
            ->setBody('Here is the message itself')
        ;

        // Send the message
        $result = $mailer->send($message);
        print_r($result);

    }

}

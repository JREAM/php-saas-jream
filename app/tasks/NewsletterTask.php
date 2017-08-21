<?php

namespace App\Tasks;

use Phalcon\Cli\Task;
use App\Models\Newsletter;
use App\Models\NewsletterSubscription;
use App\Models\User;
use Swift_SmtpTransport;
use Swift_Mailer;
use Aws\Sns\SnsClient;

class NewsletterTask extends Task
{
    public function mainAction()
    {
        echo 'This can be used to send emails to all users';
    }

    public function snsAction($newsletterId)
    {
        $newsletter = \Newsletter::findById($newsletterId);
        if (!$newsletter) {
            echo 'No Newsletter found with ID: ' . $newsletterId . PHP_EOL;
            return false;
        }

        // Publish to AWS SNS Topics to track the result from AWS SQS.
        $SNS_ARN = [
            'bounce' => 'arn:aws:sns:us-east-1:950584027081:ses-bounce-topic',
            'complaint' => 'arn:aws:sns:us-east-1:950584027081:ses-complaint-topic',
            'delivery' => 'arn:aws:sns:us-east-1:950584027081:ses-delivery-topic',
        ];

        $sns = new SnsClient([
            'version' => getenv('AWS_SNS_VERSION'),
            'region' => getenv('AWS_SNS_REGION'),
            'credentials' => [
                'key' => getenv('AWS_SNS_ACCESS_KEY'),
                'secret' => getenv('AWS_SNS_ACCESS_SECRET_KEY')
            ]
        ]);

        // Debug
//        foreach ($SNS_ARN as $key => $value) {
//            echo "<h1>{$key}</h1>";
//            $result = $sns->listSubscriptionsByTopic(['TopicArn' => $value]);
//            print_r($result);
//        }
//        die;

        // Publish to a Topic
        foreach ($SNS_ARN as $key => $value) {
            $sns->publish([
                'TopicArn' => $value,
                'Message'  => "$key / $i: Hello Test @ " . time()
            ]);
        }
    }

    /**
     * This creates the initial email list.
     *
     * @return bool
     */
    public function createListAction()
    {
        $users = User::find("is_deleted = 0 AND is_banned = 0");

        if (!$users) {
            echo 'No users found.' . PHP_EOL;
            return false;
        }

        // Traversing with a while
        $users->rewind();

        while ($users->valid()) {
            $user = $users->current();
            $email = $user->getEmail();

            $newsletterSubscriber = NewsletterSubscription::findFirst([
                "conditions" => "user_id = :id: OR email = :email:",
                "bind" => [
                    "email" => $email,
                    "id" => $user->id
                ]
            ]);

            // Add to Newsletter
            if (!$newsletterSubscriber) {
                printf("Inserting %s into newsletter_subscription.\n", $user->getEmail());
                $newsletterSubscriber = new NewsletterSubscription();
                $newsletterSubscriber->is_subscribed = 1;
                $newsletterSubscriber->user_id = $user->id;
                $newsletterSubscriber->email = $email;
                $newsletterSubscriber->token = $this->security->hash($email . random_int(1, 2500));
                $newsletterSubscriber->save();
            } else {
                echo "User already in database." . PHP_EOL;
            }

            // Plural Next (Whole Result)
            $users->next();
        }
    }

    // @TODO: This is a loop, email everybody..
    // @TODO: Read template from database, update database everytim eemail sent so it doesnt double send.
    public function sendEmailAction()
    {
        // Load AWS SES.. only send to ppl that didnt receive email id X
//        $subscribers = \NewsletterSubscription::get();

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
            ->setFrom([getenv('EMAIL_FROM_ADDR'), getenv('EMAIL_FROM_NAME')])
//            ->setTo(['example@example.org' => 'John Doe'])
            ->setTo(['imboyus@gmail.com' => 'Jesseeeee'])
            ->setBody('Here is the message itself')
        ;

        // Send the message
        $result = $mailer->send($message);
        print_r($result);
    }
}

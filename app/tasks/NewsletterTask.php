<?php

use Phalcon\Cli\Task;

class NewsletterTask extends Task
{

  public function mainAction()
  {
    echo 'This can be used to send emails to all users' . PHP_EOL;
  }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

  public function send(int $id)
  {
    $newsletter = \Newsletter::findFirstById($id);
    if (!$newsletter) {
      die('No result found');
    }

    $template = file_get_contents($this->config('emailPath') . '/newsletter-template.php');
    $search = [
      '{{ content }}',
      '{{ unsubscribe_url }}',
    ];
    $replace = [
      $newsletter->body,
      sprintf("%s/newsletter/unsubscribe", $this->di->config->url)
    ];

    $final_email = str_replace($search, $replace, $template);

        // Get Users
    $subscriptions = \NewsletterSubscription::find(['is_deleted = 0']);
    foreach ($subscriptions as $key => $value) {
            // Add to redis to queue them up
            //$this->redis->add($value->email);
    }
  }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

  /**
   * @param int $newsletterId Id of the newsletter to send
   *
   * @return bool
   */
  public function snsAction(int $newsletterId)
  {
    $newsletter = \Newsletter::findById($newsletterId);
    if (!$newsletter) {
      echo 'No Newsletter found with ID: ' . $newsletterId . PHP_EOL;
      return false;
    }

    // Publish to AWS SNS Topics to track the result from AWS SQS.
    // Turns object of key/value into array bounce, complaint, etc.
    $SNS_ARN = array($this->di->api->aws->sns->arn);

    $sns = new Aws\Sns\SnsClient([
      'version' => $di->api->aws->sns->version,
      'region' => $di->api->aws->sns->region,
      'credentials' => [
        'key' => $di->api->aws->sns->accessKey,
        'secret' => $di->api->aws->sns->secretKey,
      ],
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
        'Message' => "$key / $i: Hello Test @ " . time(),
      ]);
    }

    return;
  }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

  /**
   * This creates the initial email list.
   *
   * @return bool
   */
  public function createListAction()
  {
        // @TODO Exclude from newsletter_unsubscribed, remove the row in users.newsletter_subscribed too
    $users = \User::find("is_deleted = 0 AND is_banned = 0");

    if (!$users) {
      echo 'No users found.' . PHP_EOL;
      return false;
    }

        // Traversing with a while
    $users->rewind();

    while ($users->valid()) {
      $user = $users->current();
      $email = $user->getEmail();

      $newsletterSubscriber = \NewsletterSubscription::findFirst([
        "conditions" => "user_id = :id: OR email = :email:",
        "bind" => [
          "email" => $email,
          "id" => $user->id,
        ],
      ]);

            // Add to Newsletter
      if (!$newsletterSubscriber) {
        printf("Inserting %s into newsletter_subscription.\n", $user->getEmail());
        $newsletterSubscriber = new \NewsletterSubscription();
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

    return;
  }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    // @TODO: This is a loop, email everybody..
    // @TODO: Read template from database, update database everytim eemail sent so it doesnt double send.
  public function sendEmailAction()
  {
        // Load AWS SES.. only send to ppl that didnt receive email id X
//        $subscribers = \NewsletterSubscription::get();

        //
        // Create the Transport
    $transport = Swift_SmtpTransport::newInstance(
      $this->api->aws->ses->host,
      $this->api->aws->ses->port,
      'tls'
    )
      ->setUsername(
        $this->api->aws->ses->username,
        $this->api->aws->ses->password
      );

        // Create the Mailer using your created Transport
    $mailer = Swift_Mailer::newInstance($transport);

        // Create a message
    $message = Swift_Message::newInstance('Wonderful Subject')->setFrom([
      $this->di->config->from_address,
      $this->di->config->from_name
    ])//            ->setTo(['example@example.org' => 'John Doe'])
      ->setTo(['imboyus@gmail.com' => 'Jesseeeee'])->setBody('Here is the message itself');

        // Send the message
    $result = $mailer->send($message);
    print_r($result);

    return;
  }
}

<?php

use \Phalcon\Tag;

class TestController extends \BaseController
{

    /**
     * @return void
     */
    public function onConstruct()
    {
        if (\STAGE == 'live' && !$this->input->get('jesse=1')) {
            return $this->redirect('index');
        }
    }

    // calls the API
    public function ajaxAction()
    {
        $this->view->pick('test/ajax');
    }

    public function routeAction() {
//        echo '<pre>';
//        $r = $this->router->getRoutes();
//        print_r($r);
        die;

    }

    public function indexAction()
    {
        $SNS_ARN = [
            'bounce' => 'arn:aws:sns:us-east-1:950584027081:ses-bounce-topic',
            'complaint' => 'arn:aws:sns:us-east-1:950584027081:ses-complaint-topic',
            'delivery' => 'arn:aws:sns:us-east-1:950584027081:ses-delivery-topic',
        ];

        $sns = new Aws\Sns\SnsClient([
            'version' => getenv('AWS_SNS_VERSION'),
            'region' => getenv('AWS_SNS_REGION'),
            'credentials' => [
                'key' => getenv('AWS_SNS_ACCESS_KEY'),
                'secret' => getenv('AWS_SNS_ACCESS_SECRET_KEY')
            ]
        ]);

        echo "<pre>";
        foreach ($SNS_ARN as $key => $value) {
            echo "<h1>{$key}</h1>";
            $result = $sns->listSubscriptionsByTopic(['TopicArn' => $value]);
            print_r($result);
            echo "<hr>";
        }
        echo "</pre>";

        // Try creating SES Topics
//        $batch = new Aws\Batch\BatchClient([]);
        foreach ($SNS_ARN as $key => $value) {
            for ($i = 0; $i < 5; $i++) {
                $sns->publish([
                    'TopicArn' => $value,
                    'Message'  => "$key / $i: Hello Test @ " . time()
                ]);
            }
        }

        /// SQS

        $SQS_URLS = [
            'sqs_bounce_url' => "https://sqs.us-east-1.amazonaws.com/950584027081/ses-bounce-queue",
            'sqs_complaint_url' => "https://sqs.us-east-1.amazonaws.com/950584027081/ses-complaint-queue",
            'sqs_delivery_url' => "https://sqs.us-east-1.amazonaws.com/950584027081/ses-delivery-queue"
        ];

        $sqs = new Aws\Sqs\SqsClient([
            'version' => getenv('AWS_SQS_VERSION'),
            'region' => getenv('AWS_SQS_REGION'),
            'credentials' => [
                'key' => getenv('AWS_SQS_ACCESS_KEY'),
                'secret' => getenv('AWS_SQS_ACCESS_SECRET_KEY')
            ]
        ]);

        foreach ($SQS_URLS as $key => $value) {
            echo "<h1>{$key}</h1>";
            // Get Messages from queue
            $result = $sqs->receiveMessage($value, [
                'QueueUrl' => $value
            ]);
            print_r($result);
            echo "<hr>";
        }
        die;


    }

    public function modelAction()
    {
        \Newsletter::findFirst(['is_deleted = 0']);
        \Notification::findFirst(['is_deleted = 0']);
        \Product::findFirst(['is_deleted = 0']);
        \ProductCourse::findFirst(['is_deleted = 0']);
        \ProductCourseMeta::findFirst(['is_deleted = 0']);
        \Promotion::findFirst(['is_deleted = 0']);
        \ProductThread::findFirst(['is_deleted = 0']);
        \ProductThreadReply::findFirst(['is_deleted = 0']);
        \Transaction::findFirst(['is_deleted = 0']);
        \User::findFirst(['is_deleted = 0']);
        \UserAction::findFirst(['is_deleted = 0']);
        \UserPurchase::findFirst(['is_deleted = 0']);
        \UserReferrer::findFirst(['id = 0']);
        \UserSupport::findFirst(['is_deleted = 0']);
        \Youtube::findFirst(['is_deleted = 0']);
    }

    public function keyAction()
    {
        echo '<pre>';
        print_r($this->api);
        die;
        $this->view->pick('test/key.volt');
    }

    // --------------------------------------------------------------

    public function flashAction()
    {
        echo 'flash?';
        $z = $this->flash->output();

        echo '<pre>';

        $this->flash->message('formData', ['name' => 'jesse', 'age' => 25]);
        $z = $this->flash->getMessages('formData');
        print_r($z);

    }

    // --------------------------------------------------------------

    public function dbAction()
    {
        $batch = new Batch('user_notification');
        $batch->setRows(['user_id', 'notification_id'])
            ->setValues([
                [23, 1],
                [24, 1],
                [25, 1],
                [26, 1],
                [27, 1],
                [28, 1],
                [29, 1],
                [30, 1],
            ])->insert();

    }

    // --------------------------------------------------------------

    public function emailAction()
    {
        $e = $this->component->email->create('purchase', [
            'gateway'        => 'PayPal',
            'transaction_id' => 123,
            'product_price'  => 12,
            'product_title'  => 1.55,
        ]);

        echo $e;
        die;
    }

    // --------------------------------------------------------------

}

// End of File
// --------------------------------------------------------------

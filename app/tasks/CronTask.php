<?php

use Phalcon\Cli\Task;
use Aws\Exception\AwsException;

class CronTask extends Task
{
    // ------------------------------------------------------------------------------

    /**
     * @var object AWS SQS Client
     */
    protected $client;

    /**
     * @var array Endpoints
     */
    protected $sqsEndpoints = [
        'bounce'    => "https://sqs.us-east-1.amazonaws.com/950584027081/ses-bounce-queue",
        'complaint' => "https://sqs.us-east-1.amazonaws.com/950584027081/ses-complaint-queue",
        'delivery'  => "https://sqs.us-east-1.amazonaws.com/950584027081/ses-delivery-queue"
    ];

    // -----------------------------------------------------------------------------

    /**
     * This is a Cron Task
     */
    public function mainAction()
    {
        echo '... Cron Task ...' . PHP_EOL;
    }

    // -----------------------------------------------------------------------------

    public function deliverNewsletter()
    {
        // Check Redis for Queue
        // I could save the Rendered email in redis?
        // Send Email Rendered..


    }

    // -----------------------------------------------------------------------------

    /**
     * Reads from AWS RSR
     */
    public function sqsReceiveAction()
    {
        $this->client = new Aws\Sqs\SqsClient([
            'version'     => getenv('AWS_SQS_VERSION'),
            'region'      => getenv('AWS_SQS_REGION'),
            'credentials' => [
                'key'    => getenv('AWS_SQS_ACCESS_KEY'),
                'secret' => getenv('AWS_SQS_ACCESS_SECRET_KEY')
            ]
        ]);

        foreach ($this->sqsEndpoints as $endpointName => $endpointUrl) {
            printf("Checking SQS: %s", $endpointName);
            $this->processSqs($endpointUrl);
        }
    }

    // -----------------------------------------------------------------------------

    /**
     * Handles the SQS Results
     *
     * @param string  $endpoint  An AWS Endpoint
     */
    protected function processSqs($endpoint)
    {
        try {
            $result = $this->client->receiveMessage([
                'QueueUrl' => $endpoint, // Required
                'MaxNumberOfMessages' => 500,
                'MessageAttributeNames' => ['All'],
                'WaitTimeSeconds' => 0
            ]);

            if (count($result) > 0) {
                $messages = $result->get('Messages');
                foreach ($messages as $mkey => $mvalue) {
                    var_dump($mvalue);
                    $this->client->deleteMessage([
                        'QueueUrl' => $endpoint,
                        'ReceiptHandle' => $result->get('Messages')[$mkey]['ReceiptHandle'] // Required
                    ]);
                }
            }

            print_r($result);
        } catch (AwsException $e) {
            error_log($e->getMessage());
        }
    }

    // -----------------------------------------------------------------------------

    protected function test()
    {
        // Get Messages from queue
        $result = json_decode($result);

        // @TODO Need newsletter ID, I can probably pass in the commonHeaders
        // @TODO Need to make Crontab and ensure its always running
        switch ($result->notificationType) {
            case 'Bounce':
                $result->bounce->bounceType; // Permanent, Temporary?
                $result->bounce->bounceSubType; // Abuse, General

                $result->timestamp;
                $result->feedbackId;

                if (property_exists($result, 'remoteMtaIp')) {
                    $result->remoteMtaIp;
                }

                if (property_exists($result, 'reportingMTA')) {
                    $result->reportingMTA;
                }

                foreach ($result->bounce->bouncedRecipients as $key => $recipient) {
                    // Update Bounced
                    if (property_exists($recipient, 'emailAddress')) {
                        $recipient->emailAddress;
                    }

                    if (property_exists($recipient, 'status')) {
                        $recipient->status;
                    }

                    if (property_exists($recipient, 'action')) {
                        $recipient->action;
                    }

                    if (property_exists($recipient, 'diagnosticCode')) {
                        $recipient->diagnosticCode;
                    }
                }
                break;
            case 'Complaint':
                // http://docs.aws.amazon.com/ses/latest/DeveloperGuide/notification-examples.html
                foreach ($result->complaint->complainedRecipients as $key => $email) {
                    // Update email as complaint
                    $result->complaint->userAgent;

                    if (property_exists($result->complaint, 'complaintFeedbackType')) {
                        $result->complaint->complaintFeedbackType;
                    }
                    if (property_exists($result->complaint, 'arrivalDate')) {
                        $result->complaint->arrivalDate;
                    }
                    $result->complaint->timestamp;
                    $result->complaint->feedbackId;
                }
                break;
            case 'Delivery':
                foreach ($result->destination->email as $key => $email) {
                    // Update email is delivered;
                    $email;
                }

                $result->timestamp;
                $result->delivery->timestamp;
                $result->delivery->processingTimeMillis;
                $result->delivery->reportingMTA;
                $result->delivery->remoteMtaIp;
                $result->delivery->smtpResponse;

                break;
        }
    }

    // -----------------------------------------------------------------------------

}

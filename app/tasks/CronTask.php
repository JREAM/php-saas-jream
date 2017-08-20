<?php

use Phalcon\Cli\Task;

class CronTask extends Task
{
    public function mainAction()
    {
        echo '... Cron Task ...' . PHP_EOL;
    }

    public function sqsAction()
    {
        $SQS_URLS = [
            'sqs_bounce_url'    => "https://sqs.us-east-1.amazonaws.com/950584027081/ses-bounce-queue",
            'sqs_complaint_url' => "https://sqs.us-east-1.amazonaws.com/950584027081/ses-complaint-queue",
            'sqs_delivery_url'  => "https://sqs.us-east-1.amazonaws.com/950584027081/ses-delivery-queue"
        ];

        $sqs = new Aws\Sqs\SqsClient([
            'version'     => getenv('AWS_SQS_VERSION'),
            'region'      => getenv('AWS_SQS_REGION'),
            'credentials' => [
                'key'    => getenv('AWS_SQS_ACCESS_KEY'),
                'secret' => getenv('AWS_SQS_ACCESS_SECRET_KEY')
            ]
        ]);

        foreach ($SQS_URLS as $key => $value) {
            // Get Messages from queue
            $result = $sqs->receiveMessage($value, [
                'QueueUrl' => $value
            ]);
            print_r($result);

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
            echo "<hr>";
        }
    }
}

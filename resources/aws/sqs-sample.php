<?php
// This should be called like in a loop, crontap, or every X minutes
// http://docs.aws.amazon.com/aws-sdk-php/v2/guide/service-sqs.html

use Aws\Common\Aws;

// Create a service builder using a configuration file
$aws = Aws::factory('/path/to/my_config.json');
$client = $aws->get('Sqs');

// Default = 1 message, set to 10 max
$result = $client->receiveMessage(array(
    'QueueUrl' => $queueUrl,
    'MaxNumberOfMessages' => 10,
    'WaitTimeSeconds' => 10, # Wait for SQS to long poll, up to 20 secs
));

foreach ($result->getPath('Messages/*/Body') as $messageBody) {
    // Do something with the message
    echo $messageBody;
}

// Once a message is working and received, delete from SQS:
$result = $client->deleteMessage(array(
    // QueueUrl is required
    'QueueUrl' => 'string',
    // ReceiptHandle is required
    'ReceiptHandle' => 'string',
));

// To purge the Queue
$result = $client->purgeQueue(array(
    // QueueUrl is required
    'QueueUrl' => 'string',
));

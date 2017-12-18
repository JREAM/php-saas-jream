<?php
use Aws\S3\S3Client;
use Http\Adapter\Guzzle6\Client\GuzzleAdapter;
use SparkPost\SparkPost;

/**
 * ==============================================================
 * Email Transport to send Mail
 * =============================================================
 */
$di->setShared('s3', function () {
    return new S3Client([
        'version'     => getenv('AWS_S3_VERSION'),
        'region'      => getenv('AWS_S3_REGION'),
        'credentials' => [
            'key'    => getenv('AWS_S3_ACCESS_KEY'),
            'secret' => getenv('AWS_S3_ACCESS_SECRET_KEY='),
        ],
    ]);
});

/**
 * ==============================================================
 * Email Transport to send Mail
 * =============================================================
 */
$di->setShared('email', function (array $data) use ($di) {

    // For Debugging
    if (\APPLICATION_ENV !== \APP_PRODUCTION && getenv('DEBUG_EMAIL')) {
        $transport = (new Swift_SmtpTransport('localhost', 1025));
        $mailer    = new Swift_Mailer($transport);

        // Create a message
        $message = (new Swift_Message($data[ 'subject' ]))->setFrom([$data[ 'from_email' ] => $data[ 'from_name' ]])->setTo([$data[ 'to_email' ] => $data[ 'to_name' ]])->setBody($data[ 'content' ]);

        return $mailer->send($message);
    }

    $to      = new \SendGrid\Email($data[ 'to_name' ], $data[ 'to_email' ]);
    $from    = new \SendGrid\Email($data[ 'from_name' ], $data[ 'from_email' ]);
    $content = new \SendGrid\Content("text/html", $data[ 'content' ]);

    $mail = new \SendGrid\Mail($from, $data[ 'subject' ], $to, $content);

    $sg       = new \SendGrid(getenv('SENDGRID_KEY'));
    $response = $sg->client->mail()->send()->post($mail);

    // Catch a Non 200 Error
    if (!in_array($response->statusCode(), [200, 201, 202], true)) {
        $di->get('sentry')->captureMessage(sprintf("Headers: %s | ErrorCode: %s", $response->headers(), $response->statusCode()));
    }

    return $response;
});

/**
 * ==============================================================
 * SparkPost for Email
 * =============================================================
 */
$di->setShared('sparkpost', function (array $data) use ($di) {
    $httpClient = new GuzzleAdapter(new GuzzleHttp\Client());
    $sparky = new SparkPost($httpClient, ['key'=> getenv('SPARKPOST') ]);
    $sparky->setOptions(['async' => false]);

    $promise = $sparky->transmissions->post([
        'content' => [
            'from' => [
                'name' => 'JREAM',
                'email' => 'notify@jream.com',
            ],
            'subject' => $data['subject'],
            'html' => '<html><body><h1>Congratulations, {{name}}!</h1><p>You just sent your very first mailing!</p></body></html>',
            'text' => 'Congratulations, {{name}}!! You just sent your very first mailing!',
        ],
        'substitution_data' => ['name' => $data['name']],
        'recipients' => [
            [
                'address' => [
                    'name' => $data['name'],
                    'email' => $data['email'],
                ],
            ],
        ],
    ]);

    $promise = $sparky->transmissions->get();

    try {
        $response = $promise->wait();
        return [
            'code' => $response->getStatusCode(),
            'body' => $response->getBody()
        ];
    } catch (\Exception $e) {
        return [
            'code' => $e->getCode(),
            'msg' => $e->getMessage()
        ];
    }
});

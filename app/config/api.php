<?php

/**
 * ==============================================================
 * Website API's
 * =============================================================
 * =
 * Based on the .env configuration, it will use the values below
 * by default for test environments, or the live settings some of
 * which are provided in the .env file.
 *
 * --------------------------------------------------------------
 */

$inDevelopment = false;
if (getenv('APPLICATION_ENV') == 'development') {
  $inDevelopment = true;
}

$api = new \Phalcon\Config([
  'stripe' => [
        // Keep publishableKey, used in Views.
    'publishableKey' => ($inDevelopment) ? getenv('DEV_STRIPE_KEY') : getenv('STRIPE_KEY'),
    'secretKey' => ($inDevelopment) ? getenv('DEV_STRIPE_SECRET') : getenv('STRIPE_SECRET'),
        // secretKey    (env variable)
  ],
  'paypal' => [
        // In Services.php
    'username' => $inDevelopment ? getenv('DEV_PAYPAL_USERNAME') : getenv('PAYPAL_USERNAME'),
    'password' => $inDevelopment ? getenv('DEV_PAYPAL_PASSWORD') : getenv('PAYPAL_PASSWORD'),
    'signature' => $inDevelopment ? getenv('DEV_PAYPAL_SIGNATURE') : getenv('PAYPAL_SIGNATURE'),
    'testMode' => getenv('PAYPAL_TESTMODE'),
  ],
  'aws' => [
    'cloudfront' => [
      'url' => getenv('AWS_CLOUDFRONT_URL'), // KEEP TRAILING SLASH
      'rtmpUrl' => getenv('AWS_CLOUDFRONT_RMTP_URL'), // KEEP TRAILING SLASH
      // constants.php is loaded first, so CONFIG_DIR will be set dynamically.
      'privateKeyLocation' => $config->application->configDir . 'keys/aws-cloudfront-pk.pem',
      'expiration' => strtotime(getenv('AWS_CLOUDFRONT_EXPIRATION')),
      // Credentials In: .env / .env.sample
      // ----------------------------------
      'region' => getenv('AWS_CLOUDFRONT_REGION'),
      'version' => getenv('AWS_CLOUDFRONT_VERSION'),
      'keyPairId' => getenv('AWS_CLOUDFRONT_KEYPAIR_ID')
    ],
    'sns' => [
      'version' => getenv('AWS_SNS_VERSION'),
      'region' => getenv('AWS_SNS_REGION'),
      'accessKey' => getenv('AWS_SNS_ACCESS_KEY'),
      'secretKey' => getenv('AWS_SNS_SECRET_KEY'),
      'arn' => [
        'bounce' => getenv('AWS_SNS_ARN_BOUNCE'),
        'complaint' => getenv('AWS_SNS_ARN_COMPLAINT'),
        'delivery' => getenv('AWS_SNS_ARN_DELIVERY'),
      ]
    ],
    'ses' => [
      'host' => getenv('AWS_SES_HOST'),
      'username' => getenv('AWS_SES_USERNAME'),
      'password' => getenv('AWS_SES_PASSWORD'),
      'port' => getenv('AWS_SES_PORT'),
    ],
    'sqs' => [
      'version' => getenv('AWS_SQS_VERSION'),
      'region' => getenv('AWS_SQS_REGION'),
      'accessKey' => getenv('AWS_SQS_ACCESS_KEY'),
      'secretKey' => getenv('AWS_SQS_ACCESS_SECRET_KEY'),
    ]
  ],
  'google' => [
    'recaptchaSecret' => getenv('GOOGLE_RECAPTCHA_SECRET')
  ],
  'sparkpost' => [
    'key' => getenv('SPARKPOST_KEY')
  ],
  'sendgrid' => [
    'key' => getenv('SENDGRID_KEY'),
        // Credentials In: .env / .env.sample
        // ----------------------------------
        // host         (env variable)
        // port         (env variable)
        // username     (env variable)
        // password     (env variable)
        // key          (env variable)
  ],
  'sparkpost' => [
    'key' => getenv('SPARKPOST_KEY')
  ],
    // For the email List to use JREAM Customers
  'mailchimp' => [
        // key          (env variable)
        // listId       (env variable)
  ],
  'social_auth' => [
        // @important   More can be customized here
        // @link        https://hybridauth.github.io/developer-ref-user-authentication.html
    'providers' => [
      'google' => [
        'enabled' => true,
        'callback' => $inDevelopment ? getenv('DEV_GOOGLE_CALLBACK_URL') : getenv('GOOGLE_CALLBACK_URL'),
        'keys' => [
          'id' => $inDevelopment ? getenv('DEV_GOOGLE_CLIENT_ID') : getenv('GOOGLE_CLIENT_ID'),
          'secret' => $inDevelopment ? getenv('DEV_GOOGLE_CLIENT_SECRET') : getenv('GOOGLE_CLIENT_SECRET'),
        ],
                // 'scope' => '', // Using Default Provided
      ],
      'facebook' => [
        'enabled' => true,
        'callback' => $inDevelopment ? getenv('DEV_FACEBOOK_CALLBACK_URL') : getenv('FACEBOOK_CALLBACK_URL'),
        'keys' => [
          'id' => $inDevelopment ? getenv('DEV_FACEBOOK_APP_ID') : getenv('FACEBOOK_APP_ID'),
          'secret' => $inDevelopment ? getenv('DEV_FACEBOOK_APP_SECRET') : getenv('FACEBOOK_APP_SECRET'),
        ],
                // 'scope' => '', // Using Default Provided
      ],
      'github' => [
        'enabled' => true,
        'callback' => $inDevelopment ? getenv('DEV_GITHUB_CALLBACK_URL') : getenv('GITHUB_CALLBACK_URL'),
        'keys' => [
          'id' => $inDevelopment ? getenv('DEV_GITHUB_CLIENT_ID') : getenv('GITHUB_CLIENT_ID'),
          'secret' => $inDevelopment ? getenv('DEV_GITHUB_CLIENT_SECRET') : getenv('GITHUB_CLIENT_SECRET'),
        ],
                // 'scope' => '', // Using Default Provided
      ],
    ],
  ]
]);

return $api;

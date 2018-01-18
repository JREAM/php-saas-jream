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
      'url' => 'http://sce0kcw2h3nxo.cloudfront.net/', // KEEP TRAILING SLASH
      'rtmpUrl' => 'rtmp://sce0kcw2h3nxo.cloudfront.net/', // KEEP TRAILING SLASH
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
        'bounce' => 'arn:aws:sns:us-east-1:950584027081:ses-bounce-topic',
        'complaint' => 'arn:aws:sns:us-east-1:950584027081:ses-complaint-topic',
        'delivery' => 'arn:aws:sns:us-east-1:950584027081:ses-delivery-topic',
      ]
    ],
    'ses' => [
            // host        (env variable)
            // username    (env variable)
            // password    (env variable)
            // port        (env variable)
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
  'sendgrid' => [
    'key' => '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'
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
        'callback' => 'api/auth/google',
        'keys' => [
          'id' => $inDevelopment ? getenv('GOOGLE_CLIENT_ID_DEV') : getenv('GOOGLE_CLIENT_ID'),
          'secret' => $inDevelopment ? getenv('GOOGLE_CLIENT_SECRET_DEV') : getenv('GOOGLE_CLIENT_SECRET'),
        ],
                // 'scope' => '', // Using Default Provided
      ],
      'facebook' => [
        'enabled' => true,
        'callback' => 'api/auth/facebook',
        'keys' => [
          'id' => $inDevelopment ? getenv('FACEBOOK_APP_ID_DEV') : getenv('FACEBOOK_APP_ID'),
          'secret' => $inDevelopment ? getenv('FACEBOOK_APP_SECRET_DEV') : getenv('FACEBOOK_APP_SECRET'),
        ],
                // 'scope' => '', // Using Default Provided
      ],
      'github' => [
        'enabled' => true,
        'callback' => 'api/auth/github',
        'keys' => [
          'id' => $inDevelopment ? getenv('GITHUB_CLIENT_ID_DEV') : getenv('GITHUB_CLIENT_ID'),
          'secret' => $inDevelopment ? getenv('GITHUB_CLIENT_SECRET_DEV') : getenv('GITHUB_CLIENT_SECRET'),
        ],
                // 'scope' => '', // Using Default Provided
      ],
    ],
  ]
]);

return $api;

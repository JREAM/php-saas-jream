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

$api = new \Phalcon\Config([
    'stripe' => [
        // Keep publishableKey, used in Views.
        'publishableKey' => getenv('STRIPE_KEY'),
        // secretKey    (env variable)
    ],
    'paypal' => [
        // username     (env variable)
        // password     (env variable)
        // signature    (env variable)
        // testMode     (env variable)
    ],
    'aws' => [
        'cloudfront' => [
            // Keep URL's here, used in Views.
            'url'       => 'http://sce0kcw2h3nxo.cloudfront.net/', // KEEP TRAILING SLASH
            'rtmpUrl'   => 'rtmp://sce0kcw2h3nxo.cloudfront.net/', // KEEP TRAILING SLASH
            // constants.php is loaded first, so CONFIG_DIR will be set dynamically.
            'privateKeyLocation' => $config->get('configDir') . 'keys/aws-cloudfront-pk.pem',
            'expiration' => strtotime(getenv('AWS_CLOUDFRONT_EXPIRATION')),
            // Credentials In: .env / .env.sample
            // ----------------------------------
            // keyPairId        (env variable)
            // region       (env variable)
            // version      (env variable)
        ],
        'ses' => [
            // host        (env variable)
            // username    (env variable)
            // password    (env variable)
            // port        (env variable)
        ]
    ],
    'fb' => [
        // Credentials In: .env / .env.sample
        // ----------------------------------
        //'redirectUri' => sprintf('https://%s/user/doFacebookLogin', 'jream.com'),
        'scope'       => ['email','public_profile'],
        // FACEBOOK_APP_SECRET (env variable)
        // FACEBOOK_APP_ID (env variable)
    ],
    'google' => [
        'scopes'       => [
            Google_Service_Plus::PLUS_ME,
            Google_Service_Plus::PLUS_LOGIN,
            Google_Service_Plus::USERINFO_EMAIL,
            Google_Service_Plus::USERINFO_PROFILE,
        ],
        // client_id and client_secret are ENV vars only
        //'client_id' => getenv('GOOGLE_CLIENT_ID'),
        //'client_secret' => getenv('GOOGLE_CLIENT_SECRET'),
        // Location for Credentials (Keep RealPath and use this)
        //'credentials' => realpath( getenv('GOOGLE_CREDENTIALS_LOCATION') ),
        // GOOGLE_RECAPTCHA_SECRET (env variable)
    ],
    'sendgrid' => [
        // Credentials In: .env / .env.sample
        // ----------------------------------
        // host         (env variable)
        // port         (env variable)
        // username     (env variable)
        // password     (env variable)
        // key          (env variable)
    ],
    // For the email List to use JREAM Customers
    'mailchimp' => [
        // key          (env variable)
        // listId       (env variable)
    ]
]);

return $api;

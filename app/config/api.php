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
    'stripe'       => [
        // Keep publishableKey, used in Views.
        'publishableKey' => getenv('STRIPE_KEY'),
        // secretKey    (env variable)
    ],
    'paypal'       => [
        // username     (env variable)
        // password     (env variable)
        // signature    (env variable)
        // testMode     (env variable)
    ],
    'aws'          => [
        'cloudfront' => [
            // Keep URL's here, used in Views.
            'url'                => 'http://sce0kcw2h3nxo.cloudfront.net/', // KEEP TRAILING SLASH
            'rtmpUrl'            => 'rtmp://sce0kcw2h3nxo.cloudfront.net/', // KEEP TRAILING SLASH
            // constants.php is loaded first, so CONFIG_DIR will be set dynamically.
            'privateKeyLocation' => $config->get('configDir') . 'keys/aws-cloudfront-pk.pem',
            'expiration'         => strtotime(getenv('AWS_CLOUDFRONT_EXPIRATION')),
            // Credentials In: .env / .env.sample
            // ----------------------------------
            // keyPairId        (env variable)
            // region       (env variable)
            // version      (env variable)
        ],
        'ses'        => [
            // host        (env variable)
            // username    (env variable)
            // password    (env variable)
            // port        (env variable)
        ],
    ],
    'sendgrid'     => [
        // Credentials In: .env / .env.sample
        // ----------------------------------
        // host         (env variable)
        // port         (env variable)
        // username     (env variable)
        // password     (env variable)
        // key          (env variable)
    ],
    'sparkpost' => [
        // API key in ENV
    ],
    // For the email List to use JREAM Customers
    'mailchimp'    => [
        // key          (env variable)
        // listId       (env variable)
    ],
    'social_auth' => [
        // @important   More can be customized here
        // @link        https://hybridauth.github.io/developer-ref-user-authentication.html
        'providers' => [
            'google'   => [
                'enabled'  => true,
                'callback' => 'api/auth/google',
                'keys'     => [
                    'id'     => getenv('GOOGLE_CLIENT_ID'),
                    'secret' => getenv('GOOGLE_CLIENT_SECRET'),
                ],
                // 'scope' => '', // Using Default Provided
            ],
            'facebook' => [
                'enabled'  => true,
                'callback' => 'api/auth/facebook',
                'keys'     => [
                    'id'     => getenv('FACEBOOK_APP_ID'),
                    'secret' => getenv('FACEBOOK_APP_SECRET'),
                ],
                // 'scope' => '', // Using Default Provided
            ],
            'github' => [
                'enabled'  => true,
                'callback' => 'api/auth/github',
                'keys'     => [
                    'id'     => getenv('GITHUB_CLIENT_ID'),
                    'secret' => getenv('GITHUB_CLIENT_SECRET'),
                ],
                // 'scope' => '', // Using Default Provided
            ],
        ],
    ]
]);

return $api;

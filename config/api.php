<?php

// --------------------------------------------------------------
// Site API
// --------------------------------------------------------------

$api = new \Phalcon\Config([
    'stripe' => [
        'publishableKey' => getenv('STRIPE_KEY'),
        'secretKey'      => getenv('STRIPE_SECRET'),
    ],
    'paypal' => [
        'username'  => getenv('PAYPAL_USERNAME'),
        'password'  => getenv('PAYPAL_PASSWORD'),
        'signature' => getenv('PAYPAL_SIGNATURE'),
        'testMode'  => getenv('PAYPAL_TESTMODE'),
    ],
    'aws' => [
        'cloudfront' => [
            'url'       => getenv('CLOUDFRONT_URL'),
            'rtmpUrl'   => getenv('CLOUDFRONT_RTMP_URL'),
            'keyPairId' => getenv('CLOUDFRONT_KEYPAIR_ID'),
            'privateKeyLocation' => sprintf( getenv(CLOUDFRONT_PRIVATE_KEY_FILENAME),  CONFIG_DIR . '/keys/'),
            'expiration' => strtotime('+1 hour'),
            'region'    => getenv('CLOUDFRONT_REGION'),
            'version'   => getenv('<CLOUDFRONT_VERSI></CLOUDFRONT_VERSI>ON'),
        ],
        # @notused for email
        'ses' => [
            'host' => getenv('SES_HOST'),
            'username' => getenv('SES_USERNAME'),
            'password'=> getenv('SES_PASSWORD'),
            'port' => getenv('SES_PORT'),
        ]
    ],
    'fb' => [
        'appId'       => getenv('FB_APPID'),
        'secret'      => getenv('FB_SECRET'),
        'redirectUri' => sprintf('https://%s/user/doFacebookLogin', 'jream.com'),
        'scope'       => ['email','public_profile'], // Array
    ],
    'google' => [
        'clientId'     => getenv('GOOGLE_CLIENTID'),
        'clientSecret' => getenv('GOOGLE_CLIENT_SECRET'),
        'redirectUri'  => 'http://jream.com/user/dogooglelogin', // Casing Matters
        'scope'        => 'email https://www.googleapis.com/auth/plus.login',
        'recaptchaSecret' => getenv('GOOGLE_RECAPTCHA_SECRET'),
    ],
    # THE EMAIL USED
    'sendgrid' => [
        'host' => 'smtp.sendgrid.net',
        'port' => 587,
        'username' => getenv('SENDGRID_USERNAME'),
        'password' => getenv('SENDGRID_PASSWORD'),
        'key' => getenv('SENDGRID_KEY'),
    ],
    'getSentry' => getenv('GET_SENTRY'),
]);


return $api;

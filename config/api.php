<?php

// --------------------------------------------------------------
// Site API
// --------------------------------------------------------------

$api = new \Phalcon\Config([
    'stripe' => [
        'secretKey'      => 'sk_test_cAzHkn9xbY0H7yxRqVJYWDvS',
        'publishableKey' => 'pk_test_JofRelWDtYPIUFvDAglbOIWa'
    ],
    'paypal' => [
        'username'  => 'sales-facilitator_api1.jream.com',
        'password'  => '1373987010',
        'signature' => 'AFcWxV21C7fd0v3bYYYRCpSSRl31Ajm4hoHOMwEQddg5ptApyIrm4syu',
        'testMode'  => true
    ],
    'aws' => [
        'cloudfront' => [
            'url'       => 'http://sce0kcw2h3nxo.cloudfront.net/', // KEEP TRAILING SLASH
            'rtmpUrl'   => 'rtmp://sce0kcw2h3nxo.cloudfront.net/', // KEEP TRAILING SLASH
            'keyPairId' => 'APKAIV66CXJYYJLOBUSQ',
            'privateKeyLocation' => CONFIG_DIR . '/keys/aws-cloudfront-pk.pem',
            'expiration' => strtotime('+1 hour'),
            'region'    => 'us-east-1',
            'version'   => '2015-09-17'
        ],
        'ses' => [
            'host' => 'email-smtp.us-east-1.amazonaws.com',
            'username' => 'AKIAJM7KJZKDF7IZ74OQ',
            'password'=> 'Ahp4DzceEGlXLcgWgtpfd1TB2mDQnrA3USqmu4C4inBY',
            'port' => 587,
        ]
    ],
    'fb' => [
        'appId'       => '476490792463576',
        'secret'      => 'd958afe0f92495d5afaf9cb40e7599d9',
        'redirectUri' => sprintf('https://%s/user/doFacebookLogin', 'jream.com'),
        'scope'       => ['email','public_profile'] // Array
    ],
    'google' => [
        'clientId'     => '496878499570-bm53okg84qpegt9lhpmofoo00t3vt3b9.apps.googleusercontent.com',
        'clientSecret' => 'ecK95xRPjoNVASI_XGKpzmf-',
        'redirectUri'  => 'http://jream.com/user/dogooglelogin', // Casing Matters
        'scope'        => 'email https://www.googleapis.com/auth/plus.login',
        'recaptchaSecret' => '6LfHCAYTAAAAAJ78YVR4WL5zA1OH-jKHcIcPFiWz'
    ],
    'sendgrid' => [
        'host' => 'smtp.sendgrid.net',
        'port' => 587,
        'username' => 'imboyus@gmail.com',
        'password' => 'fvet8krM',
        'key' => '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',
    ],
    'mailchimp' => [
        'key' => 'fb602bacf8f42d47a9ae253d713d2517-us7',
        'listId' => '7f4c41488f' // For the email List to use JREAM Customers
    ],
    'getSentry' => 'https://62fa8a8348804a2d9baa590cbc639609:5dfff199c054404f939f977c9bc1cf81@app.getsentry.com/21558'
]);


return $api;

<?php

// --------------------------------------------------------------
// Site API Overwrite
// --------------------------------------------------------------

$api->stripe = (object) [
    'secretKey'      => 'sk_live_ymu8cf8WJlrpxpOIDWDxmt5w',
    'publishableKey' => 'pk_live_OuMdM8bv1YFRYUhYaUoOWRD5'
];

$api->paypal = (object) [
    'username'  => 'sales_api1.jream.com',
    'password'  => 'G8DJSGCJUP25NLL3',
    'signature' => 'AogZhlnprcyZu2GHGjV3zK0Y809OALKqtpKvhA-yQua1OtdP9zROASPF',
    'testMode'  => false
];

// --------------------------------------------------------------
// For Dev Environment (http only)
// --------------------------------------------------------------
// $api->fb->redirectUri = sprintf('http://%s/user/doFacebookLogin', 'dev.jream.com');
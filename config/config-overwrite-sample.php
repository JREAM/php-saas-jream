<?php

// --------------------------------------------------------------
// Edit the settings in this class for live or local settings
// It is applied in public/index.php
// --------------------------------------------------------------

class Overrwrite {

    public static function getConfig($config = false) {
        $config = ($config) ? $config : new \Phalcon\Config();

        // --------------------------------------------------------------
        //  Overwrite Database
        // --------------------------------------------------------------
        $config->database = (object) [
            'adapter'     => 'Mysql',
            'host'        => 'localhost',
            'username'    => 'root',
            'password'    => 'root',
            'dbname'      => 'jream'
        ];

        return $config;
    }

    public static function getApi($api= false) {
        $api = ($api) ? $api : new \Phalcon\Config();
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

        return $api;
    }

    public static function getConstants($constants= false) {
        $constants = ($constants) ? $constants: new \Phalcon\Config();

        // STAGE: "live", otherwise "developor" "dev" is ok.
        $constants['STAGE'] = 'develop';

        // URL: "https://jream.com" or "projects/jream.com" (no slash)
        $constants['URL'] = 'projects/jream.com';

        // BASE_URI: "https://jream.com" or "jream.com/"
        $constants['BASE_URI'] = '/jream.com/';

        // HTTPS: Forces HTTPS in Phalcon, yet Apache now has it default.
        $constants['HTTPS'] = false;

        // DEFAULT_TIMEZONE: This should always be UTC
        $constants['DEFAULT_TIMEZONE'] = 'UTC';

        return $constants;
    }

}

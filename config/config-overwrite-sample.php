<?php

// --------------------------------------------------------------
// Edit the settings in this class for live or local settings
// It is applied in public/index.php
// --------------------------------------------------------------

class Overwrite {

    public static function getConfig($config = false) {
        $config = ($config) ? $config : new \Phalcon\Config();

        // --------------------------------------------------------------
        //  Overwrite Database
        // --------------------------------------------------------------

        if (STAGE == 'live') {
            $config->database = (object)[
                'adapter' => 'Mysql',
                'host' => 'db.jream.com',
                'username' => 'jream',
                'password' => '@@FILLME@@',
                'dbname' => 'jream'
            ];
        }

        return $config;
    }

    public static function getApi($api= false) {
        $api = ($api) ? $api : new \Phalcon\Config();
        // --------------------------------------------------------------
        // Site API Overwrite
        // --------------------------------------------------------------

        if (STAGE == 'live') {
            $api->stripe = (object) [
                // Live Keys (For Local Dev, delete the stripe object)
                'secretKey'      => 'sk_test_Snt2YdZDlXjfqXBEmEbp6YVn',
                'publishableKey' => 'pk_test_WBntxU7He9EVilWQIZTqOWNn'
            ];

            $api->paypal = (object) [
                // Live Keys (For Local Dev, delete the paypal object)
                'username'  => 'sales_api1.jream.com',
                'password'  => 'G8DJSGCJUP25NLL3',
                'signature' => 'AogZhlnprcyZu2GHGjV3zK0Y809OALKqtpKvhA-yQua1OtdP9zROASPF',
                'testMode'  => false
            ];
        }


        // --------------------------------------------------------------
        // For Dev Environment (http only)
        // --------------------------------------------------------------
        // $api->fb->redirectUri = sprintf('http://%s/user/doFacebookLogin', 'dev.jream.com');

        return $api;
    }

    public static function getConstants($constants= false) {
        $constants = ($constants) ? $constants: new \Phalcon\Config();

        if (STAGE == 'live') {
            // STAGE: "live", otherwise "local" (so various items dont run) is ok.
            $constants['STAGE'] = 'live';

            // URL: "https://jream.com" or "projects/jream.com" (no slash)
            $constants['URL'] = 'https://jream.com';

            // BASE_URI: "https://jream.com" or "jream.com/"
            $constants['BASE_URI'] = 'htts://jream.com';

            // HTTPS: Forces HTTPS in Phalcon, yet Apache now has it default.
            $constants['HTTPS'] = true;

            // DEFAULT_TIMEZONE: This should always be UTC
            $constants['DEFAULT_TIMEZONE'] = 'UTC';
        }

        return $constants;
    }

}

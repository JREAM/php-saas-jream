<?php

namespace Library;

use \GuzzleHttp\Client;

class RecaptchaLibrary
{
    protected $di;
    protected $session;
    protected $request;
    protected $output; // #TODO where to get this? Im duplicating code several placed

    public function __construct()
    {
        $this->di = $this->get('di');
        $this->request = $this->di->get('request');
        $this->session = $this->di->getShared('session');
    }

    /**
     * Validates Google Recaptcha for Spam Prevention
     *
     * @return bool|string
     */
    public function recaptchaAction()
    {
        if($this->session->has('recaptcha') && $this->session->get('recaptcha')) {
            return true;
        }

        if(\APPLICATION_ENV === \APP_DEVELOPMENT) {
            $this->session->set('recaptcha', 1);
            return true;
        }

        // Get Recaptcha POST to Google
        $recaptcha = $this->request->getPost('g-recaptcha-response');
        $result = $this->verify($recaptcha);

        if( ! $result) {
            // Set a session so they don't try to work-around it..
            $this->session->set('recaptcha', $result);

            // Failure
            return false;
        }

        return true;
    }

    /**
     * @param $recaptcha  string  For Google POST
     *
     * @return bool
     */
    protected function verify(str $recaptcha)
    {
        $client = new Client([
            'base_uri' => 'https://google.com/recaptcha/api/',
            'timeout'  => 3.0,
        ]);

        $response = $client->request('POST', 'siteverify', [
            'query' => [
                'secret'   => getenv('GOOGLE_RECAPTCHA_SECRET'),
                'response' => $recaptcha,
            ],
        ]);


        $response = json_decpde($response->getBody());

        // @TODO: test here first
        print_r($response->getBody());
        die;

        if ( (int) $response->statusCode === 200 ) {
            return true;
        }

        return false;
    }

}

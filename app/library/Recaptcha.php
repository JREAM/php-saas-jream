<?php

namespace Library;

use \GuzzleHttp\Client;

class RecaptchaLibrary
{
    protected $di;
    protected $session;
    protected $request;
    protected $output; // #TODO where to get this? Im duplicating code several placed

    // -----------------------------------------------------------------------------

    /**
     * Pass the Session and Recaptcha String
     *
     * @param DI $session
     * @param str    $post
     */
    public function __construct($session, str $post)
    {
        $this->di = $this->get('di');
        $this->session = $session;
        $this->post = $post;
        $this->session = $session;
    }

    // -----------------------------------------------------------------------------

    /**
     * Validates Google Recaptcha for Spam Prevention
     *
     * @return bool|string
     */
    public function recaptchaAction()
    {
        // Success, Already Has it Set
        if($this->session->has('recaptcha') && $this->session->get('recaptcha')) {
            return true;
        }

        if(\APPLICATION_ENV === \APP_DEVELOPMENT) {
            $this->session->set('recaptcha', 1);
            return true;
        }

        // Get Recaptcha POST to Google
        $result = $this->verify($this->post);

        if( ! $result) {
            // Set a session so they don't try to work-around it..
            $this->session->set('recaptcha', $result);

            // Failure
            return false;
        }

        return true;
    }

    // -----------------------------------------------------------------------------

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


        $response = json_decode($response->getBody());

        // @TODO: test here first
        print_r($response->getBody());
        die;

        if ( (int) $response->statusCode === 200 ) {
            return true;
        }

        return false;
    }

    // -----------------------------------------------------------------------------

}

<?php

namespace App\Controllers\Api;

use Phalcon\Mvc\Controller;

class ApiController extends Controller
{

    /**
     * These are Generated every request.
     */
    protected $tokenKey;
    protected $token;

    /**
     * All Views are Disabled, only Output Text
     */
    public function initialize()
    {
        $this->view->disable();
    }

    /**
     * @return string JSON
     */
    public function beforeExecuteRoute()
    {
        // For AJAX Requests, Regenerate the Token.
//        if ($this->request->isAjax()) {
//            // request->get() is the $_REQUEST
//            $csrf = $this->request->getHeader('X-CSRFTOKEN');
//            if (!$csrf || strpos($csrf, ',') !== -1) {
//                return $this->output(0, 'No CSRF Token provided.');
//            }
//
//            $parts = explode(',', $csrf);
//            $tokenKey = $parts[0];
//            $token = $parts[1];
//
//            // CSRF Failed
//            if (!$this->security->checkToken($tokenKey, $token)) {
//                return $this->output(0, 'Invalid CSRF Token.');
//            }
//        }
    }

    // --------------------------------------------------------------

    /**
     * Default output for /api route.
     */
    public function indexAction()
    {
        $this->output(0, 'Hey, use the API correctly!');
    }

    // --------------------------------------------------------------

    /**
     * JSON Output
     *
     * @param  boolean             $result
     * @param  array|object|string $data (Optional)
     *
     * @return string JSON
     */
    protected function output($result, $data = null)
    {
        $output = [];
        $output['result'] = (int)$result;

        if ($result == 0) {
            $output['data'] = null;
            $output['error'] = $data;
        } else {
            $output['data'] = $data;
            $output['error'] = null;
        }

        // CSRF Tokens for every call
        $output['csrf'] = [
            'tokenKey' => $this->security->getTokenKey(),
            'token'    => $this->security->getToken(),
        ];

        $response = new \Phalcon\Http\Response();
        $response->setStatusCode(200, "OK");
        $response->setContent(json_encode($output));
        $response->send();
        exit;
    }

    // --------------------------------------------------------------
}

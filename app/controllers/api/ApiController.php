<?php
declare(strict_types=1);

namespace Controllers\Api;

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;

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
    }

// --------------------------------------------------------------

//    /**
//     * Check CSRF Calls
//     * @return string   JSON
//     */
//    public function validateTokens()
//    {
//        $tokenKey = $this->input->get('tokenKey');
//        $token    = $this->input->get('token');
//        if (!$this->security->checkToken($tokenKey, $token)) {
//            return $this->output(0, 'Invalid CSRF Token.');
//        }
//    }

// --------------------------------------------------------------

/**
 * Default output for /api route.
 */
public
function indexAction()
{
    $this->output(0, 'Hey, use the API correctly!');
}

// --------------------------------------------------------------

/**
 * JSON Output
 *
 * @param  int                 $result
 * @param  array|object|string $data (Optional)
 *
 * @return string JSON
 */
protected
function output(int $result, $data = null)
{
    $output = [];
    $output['result'] = (boolean)(int)$result;

    if ($result == 0) {
        $output['data'] = null;
        $output['error'] = $data;
    }
    else {
        $output['data'] = $data;
        $output['error'] = null;
    }

    // CSRF Tokens for every call
    $output['csrf'] = [
        'tokenKey' => $this->security->getTokenKey(),
        'token'    => $this->security->getToken(),
    ];

    $response = new Response();
    $response->setStatusCode(200, "OK");
    $response->setContent(json_encode($output));
    $response->send();
    exit;
}

// --------------------------------------------------------------
}

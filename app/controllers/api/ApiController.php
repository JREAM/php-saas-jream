<?php
declare(strict_types=1);

namespace Controllers\Api;

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;
use Library\TokenManager;

class ApiController extends Controller
{
    /**
     * @var TokenManager
     */
    protected $tokenManager;

    /**
     * All Views are Disabled, only Output Text
     */
    public function initialize()
    {
        $this->view->disable();
        $this->tokenManager = new TokenManager();
    }

    /**
     * @return string JSON
     */
    public function beforeExecuteRoute()
    {
        // --------------------------------------------------------------
        // Generate User Sessions CSRF Tokens
        // --------------------------------------------------------------
        // 1: Create a user-session CSRF Token Pair if one does NOT exist.
        // .. All Users signed in or not must have a CSRF token.
        // --------------------------------------------------------------
        if (!$this->tokenManager->hasToken()) {
            // Creates session data.
            $this->tokenManager->generate();
        }

        // Validate the Session Data for ALL Ajax calls
        $this->validateTokens();
    }

    /**
     * Check CSRF Session Token
     * @return string   JSON
     */
    public function validateTokens()
    {
        $csrfTokens = $this->request->getHeader('X-CSRFToken');
        if ($this->tokenManager->validate($csrfTokens) === false) {
            return $this->output(0, 'Invalid CSRF Token.');
        }
    }

    /**
     * Default output for /api route.
     *
     * @return string   JSON
     */
    public function indexAction()
    {
        return $this->output(0, 'Invalid usage of the API.');
    }

    /**
     * JSON Output
     *
     * @param  int      $result
     * @param  mixed    $data (Optional)
     *
     * @return string JSON
     */
    protected function output(int $result, $data = null)
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

        // CSRF Tokens for every call, though they do not change per user session, we may need them if expired (?)
        //$output['tokenKey'] = $this->tokenManager->getTokens()['tokenKey'];
        //$output['token']    = $this->tokenManager->getTokens()['token'];
        $response = new Response();
        $response->setStatusCode(200, "OK");
        $response->setContent(json_encode($output));
        $response->send();
        exit;
    }

}

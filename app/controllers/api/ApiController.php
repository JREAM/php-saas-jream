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
        // No views are used in API, all JSON calls
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
     *
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
     * @param  int   $result
     * @param  mixed   $msg  (Optional)
     * @param  array $data (Optional) Additional Data to pass to Client
     *
     * @return string JSON
     */
    protected function output(int $result, $msg = '', $data = [])
    {

        $output = [
            'result' => (int)(boolean)$result,
            'msg'    => (string)$msg,
            'data'   => (array)$data,
        ];

        $this->response->setContentType('application/json', 'UTF-8');
        $this->response->setStatusCode(200, "OK");
        $this->response->setJsonContent($output);

        return $this->response->send();

        // Kill Everything Else, just in case.
        exit;
    }

}

<?php
declare(strict_types=1);

namespace Controllers\Api;

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;
use Library\TokenManager;
use Library\Output;

class ApiController extends Controller
{
    /**
     * CSRF Exempt Routes
     * controller => name (lowercase)
     */
    protected $csrfExempt = [
        'auth::logout',
    ];
    /**
     * @var TokenManager
     */
    protected $tokenManager;

    // -----------------------------------------------------------------------------

    /**
     * All Views are Disabled, only Output Text
     */
    public function initialize() : void
    {
        // No views are used in API, all JSON calls
        $this->view->disable();
        $this->tokenManager = new TokenManager();
    }

    // -----------------------------------------------------------------------------

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


        if ($this->isCsrfExempt()) {
            return true;
        }hasToken
            $this->tokenManager->generate();
        }

        // Validate the Session Data for ALL Ajax calls
        $this->validateTokens();
    }

    // -----------------------------------------------------------------------------

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

    // -----------------------------------------------------------------------------

    /**
     * Check if call is CSRF Exempt
     *
     * @return boolean
     */
    protected function isCsrfExempt() : boolean
    {
        // Only working in API Controllers so Disregard Namespaces.
        // This produces:  controller::name
        $currentRoute = sprintf("%s::%s",
            strtolower($this->router->getControllerName()),
            strtolower($this->router->getActionName())
        );

        // See if it matches with the top of our exempt values.
        if (in_array($currentRoute, $this->csrfExempt)) {
            return true;
        }

        return false;
    }

    // -----------------------------------------------------------------------------

    /**
     * Default output for /api route.
     *
     * @return string   JSON
     */
    public function indexAction()
    {
        return $this->output(0, 'Invalid usage of the API.');
    }

    // -----------------------------------------------------------------------------

    /**
     * JSON Output
     *
     * @param  int   $result
     * @param  mixed   $msg  (Optional)
     * @param  array $data (Optional) Additional Data to pass to Client
     *
     * @return string JSON
     */
    protected function output(int $result, $msg, $data = [])
    {
        return (new Output($result, $msg))->setData($data)->send();
    }

}

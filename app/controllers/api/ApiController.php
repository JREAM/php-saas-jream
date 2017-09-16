<?php
declare(strict_types=1);

namespace Controllers\Api;

use Phalcon\Http\Response;
use Phalcon\Mvc\Controller;
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
     * Check CSRF Session Token
     *
     * @return Output JSON
     */
    public function validateTokens() : ?Output
    {
        $tokens = $this->request->getHeader('X-CSRFToken');
        if ($this->tokenManager->validate($tokens) === false) {
            return $this->output(0, 'Invalid CSRF Token.');
        }

        return null;
    }

    // -----------------------------------------------------------------------------

    /**
     * Check if call is CSRF Exempt
     *
     * @return bool
     */
    protected function isCsrfExempt() : bool
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
     * @return Output
     */
    public function indexAction() : Output
    {
        return $this->output(0, 'Invalid usage of the API.');
    }

    // -----------------------------------------------------------------------------

    /**
     * JSON Output
     *
     * @param  int   $result
     * @param  mixed $msg  (Optional)
     * @param  array $data (Optional) Additional Data, or multiple error messages to pass to Client
     *
     * @return Response JSON
     */
    protected function output(int $result, $msg, $data = []) : Response
    {
        $outgoing = new Output($result, $msg);
        return $outgoing->setData($data)
                 ->send();
    }

}

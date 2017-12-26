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
     * The passed JSON data for sub-controllers
     */
    protected $json;

    /**
     * Hybrid Auth Social Sign-in
     */
    protected $hybridauth;

    /**
     * @var TokenManager
     */
    protected $tokenManager;

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * Allow methods to run only certain types of methods.
     *
     * @param array $types  Example: $this->>apiMethods['POST', 'GET']); only allows these two methods
     *
     * @return bool|\Phalcon\Http\Response
     */
    protected function apiMethods(array $types) {
        // Uppercase the Types
        $types = array_map('strtoupper', $types);
        if (in_array('POST', $types) && !$this->request->isPost()) {
            return $this->output(0, 'Wrong API Call; Use POST.', [], 500);
        }
        if (in_array('GET', $types) && !$this->request->isGet()) {
            return $this->output(0, 'Wrong API Call; Use GET.', [], 500);
        }
        if (in_array('PUT', $types) && !$this->request->isPut()) {
            return $this->output(0, 'Wrong API Call; Use PUT.', [], 500);
        }
        if (in_array('PATCH', $types) && !$this->request->isPatch()) {
            return $this->output(0, 'Wrong API Call; Use PATCH.', [], 500);
        }
        if (in_array('DELETE', $types) && !$this->request->isDelete()) {
            return $this->output(0, 'Wrong API Call; Use DELETE.', [], 500);
        }
        return true;
    }

    /**
     * All Views are Disabled, only Output Text
     */
    public function initialize(): void
    {
        // No views are used in API, all JSON calls
        $this->view->disable();

        // JSON Used Everywhere
        $this->json = $this->request->getJsonRawBody();

        // CSRF Protection
        $this->tokenManager = new TokenManager();

        // Social Login
        $this->hybridauth   = $this->di->get('hybridAuth');
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * Check CSRF Session Token
     *
     * @return Output JSON
     */
    public function validateTokens(): ?Output
    {
        $tokens = $this->request->getHeader('X-CSRFToken');
        if ($this->tokenManager->validate($tokens) === false) {
            return $this->output(0, 'Invalid CSRF Token.');
        }

        return null;
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * Check if call is CSRF Exempt
     *
     * @return bool
     */
    protected function isCsrfExempt(): bool
    {
        // Only working in API Controllers so Disregard Namespaces.
        // This produces:  controller::name
        $currentRoute = sprintf("%s::%s", strtolower($this->router->getControllerName()), strtolower($this->router->getActionName()));

        // See if it matches with the top of our exempt values.
        if (in_array($currentRoute, $this->csrfExempt)) {
            return true;
        }

        return false;
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * Default output for /api route.
     *
     * @return Output (json)
     */
    public function indexAction()
    {
        return $this->output(0, 'Invalid API Endpoint.');
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * JSON Output
     *
     * @param  int   $result
     * @param  mixed $msg  (Optional)
     * @param  array $data (Optional) Additional Data, or multiple error messages to pass to Client
     *
     * @return Response JSON
     */
    protected function output(int $result, $msg, $data = [], int $httpSuccessCode = 200): Response
    {
        $outgoing = new Output($result, $msg);
        return $outgoing->setData($data)->send($httpSuccessCode);
    }
}

<?php
namespace Api\V1;

use \Phalcon\Mvc\Controller;

class ApiBaseController extends Controller
{

    /**
     * @return void
     */
    public function onConstruct()
    {
        parent::initialize();
    }

    // --------------------------------------------------------------

    /**
     * All Views are Disabled, only Output Text
     */
    public function initialize()
    {
        $this->view->disable();
    }

    // --------------------------------------------------------------

    /**
     * JSON Output
     *
     * @param  boolean $result
     * @param  array|object|string $data (Optional)
     *
     * @return string
     */
    protected function output($result, $data = null)
    {
        $output = [];
        $output['result'] = (int) $result;

        // CSRF Tokens for every call
        $output['token'] = $this->security->getToken();
        $output['tokenKey'] = $this->security->getTokenKey();

        if ($result == 0) {
            $output['data']  = null;
            $output['error'] = $data;
        } else {
            $output['data']  = $data;
            $output['error'] = null;
        }

        $response = new \Phalcon\Http\Response();
        $response->setStatusCode(200, "OK");
        $response->setContent(json_encode($output));
        $response->send();
        exit;
    }

    // --------------------------------------------------------------

}

// End of File
// --------------------------------------------------------------

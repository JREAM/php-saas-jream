<?php

namespace Library;

use Phalcon\Di\Injectable as DiInjectable;

/**
 * Output Usage:
 * $output = (new Library\Output(1, 'Success'))
 *     ->setData(['redirect' => 'uri'])
 *     ->send();
 */
class Output extends DiInjectable
{
    /**
     * Outgoing Data as JSON
     * @var array
     */
    private $outgoing = [
        'result' => 0,
        'msg'    => null,
        'data'   => [],
    ];

    /**
     * Constructs an outgoing JSON Response
     *
     * @param int $result 0 to fail, 1 to succeed
     * @param mixed $msg (default null)
     *
     * @return Output
     */
    public function __construct(int $result, $msg = null)
    {
        // Do not allow anything besides string or null
        if (!is_string($msg)) {
            $msg = null;
        }

        // Cast to an object, easier to use
        $this->outgoing = (object) $this->outgoing;

        // Set the results
        $this->outgoing->result = $result;
        $this->outgoing->msg    = $msg;

        return $this;
    }

    /**
     * Apply Data array to the output
     *
     * @param array $data
     *
     * @return Output
     */
    public function setData(array $data) {
        $this->outgoing->data = $data;
        return $this;
    }

    public function send()
    {
        // Get the DI Response Method
        $response = $this->di->get('response');

        // Set the Headers
        $response->setContentType('application/json', 'UTF-8');
        $response->setStatusCode(200, "OK");
        $response->setJsonContent($this->outgoing);

        // Deliver the response
        $this->response->send();

        // Kill all other activities
        exit;
    }

}

<?php

namespace Library;

use Phalcon\Http\Response;
use Phalcon\Di\Injectable as DiInjectable;

/**
 * Output Usage:
 * $output = (new Library\Output(1, 'Success'))
 *     ->setData(['redirect' => 'uri'])
 *     ->send();
 */
class Output extends DiInjectable
{
    public static $codes = [
        'error'   => 0,
        'warn'    => -1,
        'success' => 1,
        'info'    => 2,
    ];

    /**
     * Outgoing Data as JSON
     *
     * @var array
     */
    private $outgoing = [
        'result' => 0,
        'type'   => null,
        'msg'    => null,
        'data'   => [],
    ];

    // -----------------------------------------------------------------------------

    /**
     * Constructs an outgoing JSON Response
     *
     * @param int   $result 0 to fail, 1 to succeed
     * @param mixed $msg    (default null), It can be a string or array of messages
     *
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

        $this->type = array_search($result, self::$codes);
    }

    // -----------------------------------------------------------------------------

    /**
     * Get Status Codes
     *
     * @return array
     */
    public static function getCodes(): array
    {
        return self::$codes;
    }

    // -----------------------------------------------------------------------------

    /**
     * @param string $name
     *
     * @throws \InvalidArgumentException
     * @return int
     */
    public static function getCode(string $name): int
    {
        if (!isset(self::$codes[ $name ])) {
            throw new \InvalidArgumentException('Invalid Code called for Output.');
        }

        return (int) self::$codes[ $name ];
    }

    // -----------------------------------------------------------------------------

    /**
     * Apply Data array to the output
     *
     * @param array $data
     *
     * @return Output
     */
    public function setData(array $data): Output
    {
        $this->outgoing->data = $data;

        return $this;
    }

    // -----------------------------------------------------------------------------

    /**
     * @return Response
     */
    public function send(): Response
    {
        // Get the DI Response Method
        $response = $this->di->get('response');

        // Set the Headers
        $response->setContentType('application/json', 'UTF-8');
        $response->setStatusCode(200, "OK");
        $response->setJsonContent($this->outgoing);

        // Deliver the response
        return $this->response->send();

        // Kill all other activities
        exit;
    }

    // -----------------------------------------------------------------------------
}

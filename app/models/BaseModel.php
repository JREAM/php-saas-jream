<?php
declare(strict_types=1);

use Phalcon\Validation;
use Phalcon\Validation\Validator;

class BaseModel extends \Phalcon\Mvc\Model
{

    /**
     * @var array Saves on Redis or Memcached Queries
     */
    public static $_cache;

    /**
     * Changes the fields for 'created_at' and 'updated_at'
     * which are required on every table.
     */
    use Models\Traits\TimestampTrait;


    /**
     * @var object The Markdown Parser
     */
    public $parsedown = null;


    /**
     * @var object The config
     */
    protected $config;


    /**
     * @var object The api config
     */
    protected $api;

    // ------------------------------------------------------------------------------

    /**
     * Returns a list of errors
     *
     * @return boolean|string
     */
    public function getMessagesString()
    {
        if ($this->getMessages()) {
            return implode(', ', $this->getMessages());
        }

        return false;
    }

    // ------------------------------------------------------------------------------

    public function onConstruct()
    {
        $this->di = \Phalcon\DI\FactoryDefault::getDefault();
        $this->session = $this->di->get('session');
        $this->security = $this->di->get('security');

        // Make accessible to all models
        $this->config = $this->di->get('config');
        $this->api = $this->di->get('api');
    }

    // ------------------------------------------------------------------------------

    /**
     * Returns a HTML formatted list of errors
     *
     * @return boolean|string
     */
    public function getMessagesList()
    {
        if (!$this->getMessages()) {
            return false;
        }

        $output = '<ul>';
        foreach ($this->getMessages() as $message) {
            $output .= sprintf('<li>%s</li>', $message);
        }
        $output .= '</ul>';

        return $output;
    }

    // ------------------------------------------------------------------------------

    /**
     * Get the date offset
     *
     * @param  mixed $field  (Optional: created_at, updated_at, etc)
     *                       uses now by default
     *
     * @return string
     */
    public function getOffset($field = false)
    {
        $use = 'now';
        if ($field && property_exists($this, $field)) {
            $use = $this->{$field};
        }

        $timezone = strtolower($this->session->get('timezone'));

        $time = strtotime($use);

        $offset = 0;
        if ($timezone && $timezone != 'utc') {
            $userDateTime = new DateTime($use, new \DateTimeZone($timezone));
            $offset = $userDateTime->getOffset();
        }

        $userTime = (int)($time + $offset);

        return date('F jS, Y h:ia', $userTime);
    }

    // ------------------------------------------------------------------------------

    public function dateMDY($field = false)
    {
        if (!$field || !property_exists($this, $field)) {
            return false;
        }

        $time = strtotime($this->{$field});

        return date('m/d/y', $time);
    }

    // ------------------------------------------------------------------------------

    /**
     * Parses markdown for any given field
     *
     * @param   string $field
     *
     * @return  markdown
     */
    public function markdown($field)
    {
        if ($field && property_exists($this, $field)) {
            $use = $this->{$field};
        }

        // Only load once
        if ($this->parsedown == null) {
            $this->parsedown = new \Parsedown();
        }

        return $this->parsedown->parse($use);
    }

    // ------------------------------------------------------------------------------

    /**
     * Return a Generic Result from custom Model Functions to use the same format.
     *
     * @param int    $result True/False as an int, it is forced so 2 will be 1 as in true. 0 for false.
     * @param string $msg    String of error or success
     * @param null   $data   Can be any type of data
     *
     * @return \stdClass
     */
    protected function out(int $result, string $msg = '', $data = null): stdClass
    {
        $output = new \stdClass();
        $output->data = $data;

        if ($result) {
            $output->result = 1;
            $output->msg = $msg;
        } else {
            $output->result = 0;
            $output->msg = $msg;
        }

        return $output;
    }

    // ------------------------------------------------------------------------------

}

<?php

declare(strict_types=1);

use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\Model;

Model::setup([
    'events'                => true,   // Callback Hooks
    'columnRenaming'        => false,  // Why would this be allowed?
    'exceptionOnFailedSave' => false,
    'ignoreUnknownColumns'  => false, // This can be enabled if some issues arise
]);

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
        $this->session  = $this->di->get('session');
        $this->security = $this->di->get('security');

        // Make accessible to all models
        $this->config = $this->di->get('config');
        $this->api    = $this->di->get('api');
    }

    // ------------------------------------------------------------------------------

    /**
     * Returns a HTML formatted list of errors
     *
     * @TODO For two items, use a space, eg: $arg = 'div ul' will product <div><ul> ... </ul></div>
     *
     * @param string $container HTML Wrapper to surround entire container in; 'div', 'ol', etc
     * @param string $items     Each individual item; 'li', 'span',
     *
     * @return boolean|string
     */
    public function getMessagesAsHTML(string $container = 'ul', string $items = 'li')
    {
        if ( ! $this->getMessages()) {
            return false;
        }

        // Lowercase the HTML Elements
        $container = strtolower($container);
        $items     = strtolower($items);

        // Remove everything but a-z
        $container = preg_replace("/[^a-z]/", '', $container);
        $items     = preg_replace("/[^a-z]/", '', $items);

        // Build the list
        $output = sprintf("<%s>", $container);
        foreach ($this->getMessages() as $message) {
            $output .= sprintf('<%s>%s</%s>', $message, $items, $items);
        }
        $output .= sprintf("</%s>", $container);

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
    public function getDateTimeOffset($field = false): string
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
            $offset       = $userDateTime->getOffset();
        }

        $userTime = (int) ($time + $offset);

        return date('F jS, Y h:ia', $userTime);
    }

    // ------------------------------------------------------------------------------

    /**
     * @param bool $field
     *
     * @return bool|string
     */
    public function getDateMDY($field = false)
    {
        if ( ! $field || ! property_exists($this, $field)) {
            return false;
        }

        $time = strtotime($this->{$field});

        return date('m/d/y', $time);
    }

}

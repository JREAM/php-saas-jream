<?php

declare(strict_types=1);

use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\Model;

Model::setup([
    'events'                => true,    // Callback Hooks
    'columnRenaming'        => false,   // Why would this be allowed?
    'exceptionOnFailedSave' => false,
    'ignoreUnknownColumns'  => false,   // This can be enabled if some issues arise
]);

class BaseModel extends Model
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

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━-

    /**
     * The main constructor method
     * @return void
     */
    public function onConstruct(): void
    {
        $this->session  = $this->di->get('session');
        $this->security = $this->di->get('security');

        // Make accessible to all models
        $this->config = $this->di->get('config');
        $this->api    = $this->di->get('api');
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━-

    /**
     * The main initializer, set preferences for all models here
     * @sets Dynamic Update - For UPDATE only change fields that have changed
     * @docs https://docs.phalconphp.com/en/3.2/db-models#dynamic-updates
     *
     * @return void
     */
    public function initialize(): void
    {
        $this->useDynamicUpdate(true);
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━-

    /**
     * Automatically triggered when a update/create action fails.
     * @docs https://docs.phalconphp.com/en/3.2/db-models#injecting-services-into-models
     */
    public function notSaved()
    {
        // @TODO I should have a custom event handler for API and Standard
        // Obtain the flash service from the DI container
        //$flash = $this->getDI()->getFlash();
        //
        //$messages = $this->getMessages();
        //
        //// Show validation messages
        //foreach ($messages as $message) {
        //    $flash->error($message);
        //}
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━-

    /**
     * Returns a list of errors
     *
     * @return null|string
     */
    public function getMessagesString(): ?string
    {
        if ($this->getMessages()) {
            return implode(', ', $this->getMessages());
        }

        return null;
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━-

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
        if (!$this->getMessages()) {
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

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━-

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

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━-

    /**
     * @param bool $field
     *
     * @return bool|string
     */
    public function getDateMDY($field = false)
    {
        if (!$field || ! property_exists($this, $field)) {
            return false;
        }

        $time = strtotime($this->{$field});

        return date('m/d/y', $time);
    }

}

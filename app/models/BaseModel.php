<?php

class BaseModel extends \Phalcon\Mvc\Model
{
    /** Trait **/
    use Timestamp;

    /** @var object */
    public $parsedown = null;

    /** @var @var object */
    protected $config;

    /** @var @var object */
    protected $api;

    // --------------------------------------------------------------

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

    // --------------------------------------------------------------

    public function onConstruct()
    {
        $this->di = \Phalcon\DI\FactoryDefault::getDefault();
        $this->session = $this->di->get('session');
        $this->security = $this->di->get('security');

        // Make accessible to all models
        $this->config = $this->di->get('config');
        $this->api = $this->di->get('api');
    }

    // --------------------------------------------------------------

    /**
     * This fixes an odd bug.
     *
     * @return string Class Name in lowercase
     */
    public function getSource()
    {
        return strtolower(__CLASS__);
    }

    // --------------------------------------------------------------

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

    // --------------------------------------------------------------

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

    // --------------------------------------------------------------

    public function dateMDY($field = false)
    {
        if (!$field || !property_exists($this, $field)) {
            return false;
        }

        $time = strtotime($this->{$field});

        return date('m/d/y', $time);
    }

    // --------------------------------------------------------------

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

    // --------------------------------------------------------------
}

trait TimeStamp
{
    public $dateFormat = 'Y-m-d H:i:s';

    public function beforeCreate()
    {
        $this->created_at = date($this->dateFormat);
    }

    // --------------------------------------------------------------

    public function beforeUpdate()
    {
        $this->updated_at = date($this->dateFormat);
    }

    // --------------------------------------------------------------

    public function afterDelete()
    {
        $this->is_deleted = (int)1;
        $this->deleted_at = date($this->dateFormat);
    }

    // --------------------------------------------------------------
}

// End of File
// --------------------------------------------------------------

<?php

declare(strict_types=1);

namespace Forms;

use Phalcon\Forms\Form;
use Forms\Traits\GetMessageTrait;

class BaseForm extends Form
{
    use GetMessageTrait;

    /**
     * @var string  The ID for the Form DOM
     */
    protected $_formId;

    public function __construct(?object $entity = null, ?array $userOptions = null)
    {
        // Cast to Array for JSON or whatever
        if (!is_array($userOptions)) {
            $userOptions = (array) $userOptions;
        }
        parent::__construct($entity, $userOptions);
    }

    public function isValid($data = null, $entity = null)
    {
        // Casting JSON object to Array from API
        if (!is_array($data)) {
            $data = (array) $data;
        }
        print_r($data);
        $this->data = $data;
    }

    public function initialize(): void
    {
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
}

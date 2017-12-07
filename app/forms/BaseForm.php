<?php

declare(strict_types=1);

namespace Forms;

use Phalcon\Forms\Element;
use Phalcon\Validation\Validator;
use Phalcon\Forms\Form;
use Forms\Traits\GetMessageTrait;

class BaseForm extends \Phalcon\Forms\Form
{
    use GetMessageTrait;

    /** The ID for the DOM Form */
    protected $_formId;

    public function initialize(): void
    {
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
}

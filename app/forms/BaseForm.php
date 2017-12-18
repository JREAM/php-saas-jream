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

    public function initialize(): void
    {
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
}

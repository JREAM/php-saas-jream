<?php
declare(strict_types=1);

use Phalcon\Mvc\Model\Behavior\SoftDelete;
use Phalcon\Validation;
use Phalcon\Validation\Validator;

class Newsletter extends BaseModel
{
    /**
     * @var Table Rows
     */
    public $id;
    public $subject;
    public $body;
    public $is_sent;

    // -----------------------------------------------------------------------------

    /**
     * Phalcons std initializer when model is ready
     *
     * @return void
     */
    public function initialize() : void
    {
        /** DB Table Name */
        $this->setSource('newsletter');

        $this->addBehavior(new SoftDelete([
            'field' => 'is_deleted',
            'value' => 1,
        ]));

        // Some won't have accounts, but if they do associate them with the newsletter.
    }

    // -----------------------------------------------------------------------------

}

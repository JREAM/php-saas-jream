<?php

declare(strict_types=1);

use Phalcon\Mvc\Model\Behavior\SoftDelete;
use Phalcon\Validation;
use Phalcon\Validation\Validator;

class NewsletterResult extends BaseModel
{

    /**
     * @var Table Rows
     */
    public $id;
    public $user_id;
    public $newsletter_id;
    public $newsletter_subscriber_id;
    public $result;

    // -----------------------------------------------------------------------------

    /**
     * Phalcons std initializer when model is ready
     *
     * @return void
     */
    public function initialize(): void
    {
        /** DB Table Name */
        $this->setSource('newsletter_results');

        $this->addBehavior(new SoftDelete([
            'field' => 'is_deleted',
            'value' => 1,
        ]));
    }

    // -----------------------------------------------------------------------------
}

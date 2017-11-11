<?php

declare(strict_types=1);

use Phalcon\Mvc\Model\Behavior\SoftDelete;
use Phalcon\Validation;
use Phalcon\Validation\Validator;

class NewsletterSubscription extends BaseModel
{

    /**
     * @var Table Rows
     */
    public $id;
    public $user_id;
    public $token;
    public $email;
    public $is_verified;
    public $is_subscribed;

    // -----------------------------------------------------------------------------

    /**
     * Phalcons std initializer when model is ready
     *
     * @return void
     */
    public function initialize(): void
    {
        /** DB Table Name */
        $this->setSource('newsletter_subscription');

        $this->addBehavior(new SoftDelete([
            'field' => 'is_deleted',
            'value' => 1,
        ]));

        // Some won't have accounts, but if they do associate them with the newsletter.
        $this->belongsTo("user_id", "User", "id");
    }

    // -----------------------------------------------------------------------------
}

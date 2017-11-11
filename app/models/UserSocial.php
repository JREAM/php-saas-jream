<?php

declare(strict_types=1);

use Phalcon\Mvc\Model\Behavior\SoftDelete;
use Phalcon\Validation;
use Phalcon\Validation\Validator;

class UserAction extends BaseModel
{
    /**
     * @var Table Rows
     */
    public $id;
    public $user_id;
    public $network;
    public $identifier;
    public $display_name;
    public $profile_url;
    public $photo_url;
    public $email;
    public $is_deleted;
    public $deleted_at;
    public $created_at;
    public $updated_at;

    // -----------------------------------------------------------------------------

    /**
     * Phalcons std initializer when model is ready
     *
     * @return void
     */
    public function initialize(): void
    {
        /** DB Table Name */
        $this->setSource('user_social');

        $this->addBehavior(new SoftDelete([
            'field' => 'is_deleted',
            'value' => 1,
        ]));

        $this->belongsTo("user_id", "User", "id");
    }

    // -----------------------------------------------------------------------------
}

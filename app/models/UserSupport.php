<?php
declare(strict_types=1);

use Phalcon\Mvc\Model\Validator\StringLength;
use Phalcon\Mvc\Model\Behavior\SoftDelete;
use Phalcon\Validation;
use Phalcon\Validation\Validator;

class UserSupport extends BaseModel
{

    /**
     * @var Table Rows
     */
    public $id;
    public $user_id;
    public $type;
    public $title;
    public $content;
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
    public function initialize() : void
    {
        /** DB Table Name */
        $this->setSource('user_support');

        $this->addBehavior(new SoftDelete([
            'field' => 'is_deleted',
            'value' => 1,
        ]));

        $this->belongsTo("user_id", "User", "id");
    }

    // -----------------------------------------------------------------------------

    public function validationX()
    {
        $this->validate(new StringLength([
            'field'          => 'title',
            'min'            => 5,
            'max'            => 100,
            'messageMinimum' => 'Your message is too short, it must be atleast 5 characters.',
            'messageMaximum' => 'Your message is too long',
        ]));

        $this->validate(new StringLength([
            'field'          => 'content',
            'min'            => 10,
            'max'            => 4000,
            'messageMinimum' => 'Your message is too short, it must be atleast 10 characters.',
            'messageMaximum' => 'Your message is too long',
        ]));

        if ($this->validationHasFailed() == true) {
            return false;
        }

        return true;
    }

    // -----------------------------------------------------------------------------

}

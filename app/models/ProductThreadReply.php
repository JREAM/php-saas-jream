<?php

declare(strict_types=1);

use Phalcon\Mvc\Model\Validator\StringLength;
use Phalcon\Mvc\Model\Behavior\SoftDelete;

class ProductThreadReply extends BaseModel
{

    /**
     * @var Table Rows
     */
    public $id;
    public $product_thread_id;
    public $user_id;
    public $content;
    public $content_code;
    public $is_deleted;
    public $deleted_at;
    public $created_at;
    public $updated_at;

    // -----------------------------------------------------------------------------

    public function initialize()
    {
        /** DB Table Name */
        $this->setSource('product_thread_reply');

        $this->addBehavior(new SoftDelete([
            'field' => 'is_deleted',
            'value' => 1,
        ]));

        $this->belongsTo("product_id", "ProductThread", "id");
        $this->belongsTo("user_id", "User", "id");
    }

    // -----------------------------------------------------------------------------

    public function validationX()
    {
        $this->validate(new StringLength([
            'field'          => 'content',
            'min'            => 1,
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

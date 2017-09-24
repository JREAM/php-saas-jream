<?php
declare(strict_types=1);

use Phalcon\Mvc\Model\Behavior\SoftDelete;
use Phalcon\Validation;
use Phalcon\Validation\Validator;

class Transaction extends BaseModel
{

    /**
     * @var Table Rows
     */
    public $id;
    public $user_id;
    public $transaction_id;
    public $type;
    public $gateway;
    public $amount;
    public $amount_after_discount;
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
        $this->setSource('transaction');

        $this->addBehavior(new SoftDelete([
            'field' => 'is_deleted',
            'value' => 1,
        ]));

        $this->belongsTo("user_id", "User", "id");
        $this->hasOne("product_id", "Product", "id");
    }

    // -----------------------------------------------------------------------------

}

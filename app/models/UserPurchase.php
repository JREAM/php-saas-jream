<?php

declare(strict_types=1);

use Phalcon\Mvc\Model\Behavior\SoftDelete;
use Phalcon\Validation;
use Phalcon\Validation\Validator;

class UserPurchase extends BaseModel
{
    /**
     * @var Table Rows
     */
    public $id;
    public $user_id;
    public $product_id;
    public $transaction_id;
    public $promotion_id;
    public $promo_code; // old
    public $is_deleted;
    public $deleted_at;
    public $created_at;
    public $updated_at;

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * Phalcons std initializer when model is ready
     *
     * @return void
     */
    public function initialize(): void
    {
        /** DB Table Name */
        $this->setSource('user_purchase');

        $this->addBehavior(new SoftDelete([
            'field' => 'is_deleted',
            'value' => 1,
        ]));

        $this->belongsTo("user_id", "User", "id");
        $this->hasOne("product_id", "Product", "id");
        $this->hasOne("promotion_id", "Promotion", "id");
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
}

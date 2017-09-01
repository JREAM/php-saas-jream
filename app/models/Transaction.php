<?php
declare(strict_types=1);

use Phalcon\Mvc\Model\Behavior\SoftDelete;
use Phalcon\Validation;
use Phalcon\Validation\Validator;

class Transaction extends BaseModel
{

    /** @var array Saves on Memcached Queries */
    public static $_cache;

    public function initialize()
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

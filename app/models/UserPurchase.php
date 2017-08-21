<?php

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class UserPurchase extends BaseModel
{

    /** @const SOURCE the table name */
    const SOURCE = 'user_purchase';

    /** @var array Saves on Memcached Queries */
    public static $_cache;

    public function initialize()
    {
        $this->addBehavior(new SoftDelete([
            'field' => 'is_deleted',
            'value' => 1,
        ]));

        $this->setSource(self::SOURCE);
        $this->belongsTo("user_id", "User", "id");
        $this->hasOne("product_id", "Product", "id");
        $this->hasOne("transaction_id", "Transaction", "id");
        $this->hasOne("promotion_id", "Promotion", "id");
    }

    // --------------------------------------------------------------

    /**
     * This fixes an odd bug.
     *
     * @return string Class Name in lowercase
     */
    public function getSource()
    {
        return self::SOURCE;
    }

    // --------------------------------------------------------------

    public function afterCreate()
    {
        $this->created_at = getDateTime();
        $this->save();
    }

    // --------------------------------------------------------------

    public function afterUpdate()
    {
        $this->created_at = getDateTime();
        $this->save();
    }

    // --------------------------------------------------------------
}

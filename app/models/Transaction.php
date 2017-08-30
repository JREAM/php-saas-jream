<?php
declare(strict_types=1);

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Transaction extends BaseModel
{

    // ----------------------------------------------------------------------------

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

    // ----------------------------------------------------------------------------

    /**
     * This fixes an odd bug.
     *
     * @return string Class Name in lowercase
     */
    public function getSource()
    {
        return self::SOURCE;
    }

    // ----------------------------------------------------------------------------

}

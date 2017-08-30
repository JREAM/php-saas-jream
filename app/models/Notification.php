<?php
declare(strict_types=1);

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Notification extends BaseModel
{

    // ----------------------------------------------------------------------------

    /** @var array Saves on Memcached Queries */
    public static $_cache;

    public function initialize()
    {
        /** DB Table Name */
        $this->setSource('notification');

        $this->addBehavior(new SoftDelete([
            'field' => 'is_deleted',
            'value' => 1,
        ]));

        $this->hasOne("product_id", "Product", "id");
    }

    // ----------------------------------------------------------------------------
}

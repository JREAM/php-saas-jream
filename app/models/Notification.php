<?php
declare(strict_types=1);

use Phalcon\Mvc\Model\Behavior\SoftDelete;
use Phalcon\Validation;
use Phalcon\Validation\Validator;

class Notification extends BaseModel
{

    /** @var array Saves on Memcached Queries */
    public static $_cache;

    public $id;
    public $product_id;
    public $user_id;
    public $has_read;
    public $content;


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

    // -----------------------------------------------------------------------------
}

<?php
declare(strict_types=1);

use Phalcon\Mvc\Model\Behavior\SoftDelete;
use Phalcon\Validation;
use Phalcon\Validation\Validator;

class Newsletter extends BaseModel
{

    /** @var array Saves on Memcached Queries */
    public static $_cache;

    public $id;
    public $subject;
    public $body;
    public $is_sent;

    public function initialize()
    {
        /** DB Table Name */
        $this->setSource('newsletter');

        $this->addBehavior(new SoftDelete([
            'field' => 'is_deleted',
            'value' => 1,
        ]));

        // Some won't have accounts, but if they do associate them with the newsletter.
    }

    // -----------------------------------------------------------------------------

}

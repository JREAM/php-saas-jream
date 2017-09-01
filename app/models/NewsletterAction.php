<?php
declare(strict_types=1);

use Phalcon\Mvc\Model\Behavior\SoftDelete;
use Phalcon\Validation;
use Phalcon\Validation\Validator;

class NewsletterAction extends BaseModel
{

    /** @var array Saves on Memcached Queries */
    public static $_cache;

    public function initialize()
    {
        /** DB Table Name */
        $this->setSource('newsletter_action');

        $this->addBehavior(new SoftDelete([
            'field' => 'is_deleted',
            'value' => 1,
        ]));
    }

    // -----------------------------------------------------------------------------

}

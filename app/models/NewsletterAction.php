<?php
declare(strict_types=1);

use Phalcon\Mvc\Model\Behavior\SoftDelete;
use Phalcon\Validation;
use Phalcon\Validation\Validator;

class NewsletterResults extends BaseModel
{

    public $id;
    public $user_id;
    public $newsletter_id;
    public $newsletter_subscriber_id;
    public $result;


    /** @var array Saves on Memcached Queries */
    public static $_cache;

    public function initialize()
    {
        /** DB Table Name */
        $this->setSource('newsletter_results');

        $this->addBehavior(new SoftDelete([
            'field' => 'is_deleted',
            'value' => 1,
        ]));
    }

    // -----------------------------------------------------------------------------

}

<?php
declare(strict_types=1);

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class NewsletterSubscription extends BaseModel
{

    // ----------------------------------------------------------------------------

    /** @var array Saves on Memcached Queries */
    public static $_cache;

    public function initialize()
    {
        /** DB Table Name */
        $this->setSource('newsletter_subscription');

        $this->addBehavior(new SoftDelete([
            'field' => 'is_deleted',
            'value' => 1,
        ]));

        // Some won't have accounts, but if they do associate them with the newsletter.
        $this->belongsTo("user_id", "User", "id");
    }

    // ----------------------------------------------------------------------------

}

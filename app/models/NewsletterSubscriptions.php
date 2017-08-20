<?php

use Phalcon\Mvc\Model\Behavior\SoftDelete;
use Library\GenericResult as R;

class NewsletterSubscriptions extends BaseModel
{

    /** @const SOURCE the table name */
    const SOURCE = 'newsletter_subscriptions';

    /** @var array Saves on Memcached Queries */
    public static $_cache;

    public function initialize()
    {
        $this->addBehavior(new SoftDelete([
            'field' => 'is_deleted',
            'value' => 1,
        ]));

        $this->setSource(self::SOURCE);
        // Some won't have accounts, but if they do associate them with the newsletter.
        $this->belongsTo("user_id", "User", "id");
    }

    // --------------------------------------------------------------

    public function afterCreate()
    {
        $this->created_at = getDateTime();
        $this->token = $this->getDi()->getShared('security')->hash(random_int(1000, 50000));
        $this->save();
    }

    // --------------------------------------------------------------

    public function afterUpdate()
    {
        $this->created_at = getDateTime();
        $this->save();
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
}

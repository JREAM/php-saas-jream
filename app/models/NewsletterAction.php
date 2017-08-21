<?php

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class NewsletterAction extends BaseModel
{

    /** @const SOURCE the table name */
    const SOURCE = 'newsletter_action';

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
        // $this->belongsTo("user_id", "User", "id");
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

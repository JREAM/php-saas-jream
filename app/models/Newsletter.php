<?php

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Newsletter extends BaseModel
{

    /** @const SOURCE the table name */
    const SOURCE = 'newsletter';

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
        $this->token = hash('512', $this->email . random_int(1, 2500));
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

// End of File
// --------------------------------------------------------------

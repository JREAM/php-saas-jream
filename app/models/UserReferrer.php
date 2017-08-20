<?php

use Phalcon\Mvc\Model\Behavior\SoftDelete;
use Library\GenericResult as R;

class UserReferrer extends BaseModel
{

    /** @const SOURCE the table name */
    const SOURCE = 'user_referrer';

    /** @var array Saves on Memcached Queries */
    public static $_cache;

    public function initialize()
    {
        $this->setSource(self::SOURCE);
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

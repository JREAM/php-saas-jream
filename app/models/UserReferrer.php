<?php
use Phalcon\Mvc\Model\Behavior\SoftDelete;

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
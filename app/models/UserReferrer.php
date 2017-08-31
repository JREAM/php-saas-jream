<?php
declare(strict_types=1);

class UserReferrer extends BaseModel
{

    // -----------------------------------------------------------------------------

    /** @var array Saves on Memcached Queries */
    public static $_cache;

    public function initialize()
    {
        /** DB Table Name */
        $this->setSource('user_referrer');
    }

    // -----------------------------------------------------------------------------

}

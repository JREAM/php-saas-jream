<?php
declare(strict_types=1);

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Newsletter extends BaseModel
{

    // ----------------------------------------------------------------------------

    /** @var array Saves on Memcached Queries */
    public static $_cache;

    public function initialize()
    {
        /** DB Table Name */
        $this->setSource('newsletter');

        $this->addBehavior(new SoftDelete([
            'field' => 'is_deleted',
            'value' => 1,
        ]));

        $this->setSource(self::SOURCE);
        // Some won't have accounts, but if they do associate them with the newsletter.
    }

    // ----------------------------------------------------------------------------

}

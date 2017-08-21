<?php

use Phalcon\Mvc\Model\Validator\StringLength;
use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Youtube extends BaseModel
{
    const SOURCE = 'youtube';

    /** @var array Saves on Memcached Queries */
    public static $_cache;

    public function initialize()
    {
        $this->addBehavior(new SoftDelete([
            'field' => 'is_deleted',
            'value' => 1,
        ]));

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

    // ----------------------------------------w----------------------
}


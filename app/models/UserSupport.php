<?php

use Phalcon\Mvc\Model\Validator\StringLength;
use Phalcon\Mvc\Model\Behavior\SoftDelete;

class UserSupport extends BaseModel
{
    /** @const SOURCE the table name */
    const SOURCE = 'user_support';

    /** @var array Saves on Memcached Queries */
    public static $_cache;

    public function initialize()
    {
        $this->addBehavior(new SoftDelete([
            'field' => 'is_deleted',
            'value' => 1,
        ]));

        $this->setSource(self::SOURCE);
        $this->belongsTo("user_id", "User", "id");
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

    public function validationX()
    {
        $this->validate(new StringLength([
            'field'          => 'title',
            'min'            => 5,
            'max'            => 100,
            'messageMinimum' => 'Your message is too short, it must be atleast 5 characters.',
            'messageMaximum' => 'Your message is too long',
        ]));

        $this->validate(new StringLength([
            'field'          => 'content',
            'min'            => 10,
            'max'            => 4000,
            'messageMinimum' => 'Your message is too short, it must be atleast 10 characters.',
            'messageMaximum' => 'Your message is too long',
        ]));

        if ($this->validationHasFailed() == true) {
            return false;
        }
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

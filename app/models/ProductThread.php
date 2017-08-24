<?php
declare(strict_types=1);

use Phalcon\Mvc\Model\Validator\StringLength;
use Phalcon\Mvc\Model\Behavior\SoftDelete;

class ProductThread extends BaseModel
{

    /** @const SOURCE the table name */
    const SOURCE = 'product_thread';

    /** @var array Saves on Memcached Queries */
    public static $_cache;

    public function initialize()
    {
        $this->addBehavior(new SoftDelete([
            'field' => 'is_deleted',
            'value' => 1,
        ]));

        $this->setSource(self::SOURCE);
        $this->belongsTo("product_id", "Product", "id");
        $this->belongsTo("user_id", "User", "id");
        $this->hasMany("id", "ProductThreadReply", "product_thread_id");
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

    // @Thi is broken
    public function validationX()
    {
        $this->validate(new StringLength([
            'field'          => 'title',
            'min'            => 1,
            'max'            => 100,
            'messageMinimum' => 'Your message is too short, it must be atleast 5 characters.',
            'messageMaximum' => 'Your message is too long',
        ]));

        $this->validate(new StringLength([
            'field'          => 'content',
            'min'            => 1,
            'max'            => 4000,
            'messageMinimum' => 'Your message is too short, it must be atleast 10 characters.',
            'messageMaximum' => 'Your message is too long',
        ]));

        if ($this->validationHasFailed() == true) {
            return false;
        }
    }

    // --------------------------------------------------------------
}

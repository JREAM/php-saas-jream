<?php
use Phalcon\Mvc\Model\Validator\StringLength,
    Phalcon\Mvc\Model\Behavior\SoftDelete;

class ProductThreadReply extends BaseModel
{

    /** @const SOURCE the table name */
    const SOURCE = 'product_thread_reply';

    /** @var array Saves on Memcached Queries */
    public static $_cache;

    public function initialize()
    {
        $this->addBehavior(new SoftDelete([
            'field' => 'is_deleted',
            'value' => 1
        ]));

        $this->setSource(self::SOURCE);
        $this->belongsTo("product_id", "ProductThread", "id");
        $this->belongsTo("user_id", "User", "id");
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

    public function validationX()
    {
        $this->validate(new StringLength([
            'field' => 'content',
            'min' => 1,
            'max' => 4000,
            'messageMinimum' => 'Your message is too short, it must be atleast 10 characters.',
            'messageMaximum' => 'Your message is too long',
        ]));

        if ($this->validationHasFailed() == true) {
            return false;
        }
    }

    // --------------------------------------------------------------

}

// End of File
// --------------------------------------------------------------

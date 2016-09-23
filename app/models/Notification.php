<?php
use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Notification extends BaseModel
{

    /** @const SOURCE the table name */
    const SOURCE = 'notification';

    /** @var array Saves on Memcached Queries */
    public static $_cache;

    public function initialize()
    {
        $this->addBehavior(new SoftDelete([
            'field' => 'is_deleted',
            'value' => 1
        ]));

        $this->setSource(self::SOURCE);
        $this->hasOne("product_id", "Product", "id");
        $this->belongsTo("notification_id", "Notification", "id");
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
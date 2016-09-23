<?php
use Phalcon\Mvc\Model\Behavior\SoftDelete;

class ProductPromo extends BaseModel
{

    /** @const SOURCE the table name */
    const SOURCE = 'product_promo';

    /** @var array Saves on Memcached Queries */
    public static $_cache;

    public function initialize()
    {
        $this->addBehavior(new SoftDelete([
            'field' => 'is_deleted',
            'value' => 1
        ]));

        $this->setSource(self::SOURCE);
        $this->belongsTo("product_id", "Product", "id");
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

    public function check($code, $product_id)
    {
        $datetime = date('Y-m-d H:i:s', strtotime('now'));

        $promo = \ProductPromo::findFirst([
           "code = :code:
           AND product_id = :product_id:
           AND is_deleted = 0
           AND DATE(expires_on) < :datetime:
           ",
            "bind" => [
                "code" => $code,
                "product_id" => $product_id,
                "datetime" => $datetime
            ]
        ]);


        if (!$promo) {
            return false;
        }

        return $promo->percent_off;
    }

    // --------------------------------------------------------------

}

// End of File
// --------------------------------------------------------------
<?php

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Promotion extends BaseModel
{

    /** @const SOURCE the table name */
    const SOURCE = 'promotion';

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

    // --------------------------------------------------------------

    /**
     * Check if a Promotion Code Is Available
     *
     * @param string    $code
     * @param int|array $productId
     *
     * @return bool|\Phalcon\Mvc\Model
     */
    public function check(string $code, $productId)
    {

        if (!is_array($productId)) {
            $productId = [$productId];
        }

        $productId = implode(',', $productId);


        $promo = \Promotion::findFirst([
            "code = :code:
            AND product_id IN (:product_id:)
            AND deleted_at = 0
            AND DATE(expires_at) < :datetime:
            ",
            "bind" => [
                "code"       => $code,
                "product_id" => $productId,
                'datetime'   => date('Y-m-d H:i:s', strtotime('now')),
            ],
        ]);


        if (!$promo) {
            return false;
        }

        return $promo;
    }

    // --------------------------------------------------------------

}

// End of File
// --------------------------------------------------------------

<?php
declare(strict_types=1);

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Promotion extends BaseModel
{

    // ----------------------------------------------------------------------------

    /** @var array Saves on Memcached Queries */
    public static $_cache;

    public function initialize()
    {
        /** DB Table Name */
        $this->setSource('promotion');

        $this->addBehavior(new SoftDelete([
            'field' => 'is_deleted',
            'value' => 1,
        ]));
    }

    // ----------------------------------------------------------------------------

    /**
     * Check if a Promotion Code Is Available
     *
     * @param string    $code
     * @param int|array $productId
     *
     * @return bool|object|\Phalcon\Mvc\Model
     */
    public function check(string $code, $productId)
    {
        if (!is_array($productId)) {
            $productId = [$productId];
        }

        $productId = implode(',', $productId);

        $result = \Promotion::findFirst([
            "code = :code:
            AND product_id IN (:product_id:)
            AND deleted_at = 0
            AND DATE(expires_at) < :datetime:
            ",
            "bind" => [
                "code"       => $code,
                "product_id" => $productId,
                'datetime'   => getDateTime()
            ],
        ]);

        if (!$result) {
            return (object) [
                'code' => 0,
                'data' => null,
                'success' => '',
                'error' => 'Invalid Promotion Code.'
            ];
        }


        // The USER ID (If Set) Is checked when the Cookie is created, it won't get to this
        // point, or shouldn't -- but I'll double protect anyways.
        if ($result->user_id && $this->session->get('id') != $result->user_id) {
            return (object) [
                'code' => 0,
                'data' => null,
                'success' => '',
                'error' => 'This promotion is for an individual only, it does not appear to be you. If so, ensure you are logged in!'
            ];
        }

        // If ProductID is set, ensure they are applying correctly
        if ($result->product_id && $productId != $result->product_id) {
            $other_product = \Product::getById($result->product_id);
            return (object) [
                'code' => 0,
                'data' => null,
                'success' => '',
                'error' => sprintf('This promotion is only for: %s', $other_product->title)
            ];
        }

        // Make sure to check this DURING the checkout
        if ($result->expires_at > getDateTime()) {
            return (object) [
                'code' => 0,
                'data' => null,
                'success' => '',
                'error' => sprintf('Sorry, this promotion expired on: %s', $result->expires_at)
            ];
        }

        // Make sure to check this DURING the checkout
        if ($result->deleted_at) {
            return (object) [
                'code' => 0,
                'data' => null,
                'success' => '',
                'error' => sprintf('Sorry, this promotion was deleted on: %s ', $result->deleted_at)
            ];
        }

        // Only one of these apply
        if ($result->percent_off) {
            if ($result->percent_off <= 0 && $percent_off >= 100) {
                return (object) [
                    'code' => 0,
                    'data' => null,
                    'success' => '',
                    'error' => 'The data is invalid, percent_off must be > 0 and < 100'
                ];
            }
            $method = 'percent_off';
            $success = sprintf(
                "Price marked down from %s to %s at %s percent off using promotional code %s.",
                number_format($product->price - ($product->price * $result->percent_off), 2),
                $result->percent_off,
                $result->code
            );
            $promotional_price = number_format($product->price - ($product->price * $result->percent_off), 2);
        } elseif ($result->price) {
            if ($result->price >= $product->price) {
                return (object) [
                    'code' => 0,
                    'data' => null,
                    'success' => '',
                    'error' => 'The data is invalid, price must be < product price.'
                ];
            }

            $method = 'price';
            $success = sprintf(
                "Price marked down from %s to %s using promotional code %s.",
                number_format($product->price, 2),
                number_format($result['price'], 2),
                $result->code
            );

            $promotional_price = $result->price;
        }

        return (object) [
            'code' => 1,
            'data' => (object) [
                'method' => $method,
                'promotional_price' => $promotional_price
            ],
            'success' => $success,
            'error' => ''
        ];
    }

    // ----------------------------------------------------------------------------

}

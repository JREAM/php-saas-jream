<?php
declare(strict_types=1);

use Phalcon\Mvc\Model\Behavior\SoftDelete;
use Phalcon\Validation;
use Phalcon\Validation\Validator;

class Product extends BaseModel
{

    // -----------------------------------------------------------------------------

    /** @var array Saves on Memcached Queries */
    public static $_cache;

    const STATUS_PLANNED = 'planned';
    const STATUS_DEVELOPMENT = 'development';
    const STATUS_PUBLISHED = 'published';

    // -----------------------------------------------------------------------------

    public function initialize()
    {
        /** DB Table Name */
        $this->setSource('product');

        $this->addBehavior(new SoftDelete([
            'field' => 'is_deleted',
            'value' => 1,
        ]));

        $this->hasMany('id', 'UserPurchase', 'product_id');
        $this->hasMany('id', 'ProductCourse', 'product_id');
    }

    // -----------------------------------------------------------------------------

    public function getTags()
    {
        return explode(',', $this->tags);
    }

    // -----------------------------------------------------------------------------

    /**
     * Has a user Purchased this product?
     *
     * @param bool $userId Default is the user ID
     *
     * @return bool
     */
    public function hasPurchased($userId = false)
    {
        // Admin can go anywhere
        // if ($this->session->role === 'admin') {
        //     return true;
        // }

        if ($userId === false) {
            $userId = $this->session->get('id');
        }

        $userPurchase = \UserPurchase::findFirst([
            'product_id = :pid: AND user_id = :id:',
            'bind' => [
                'pid' => $this->id,
                'id'  => $userId,
            ],
        ]);

        if (!$userPurchase) {
            return false;
        }

        return true;
    }

    // -----------------------------------------------------------------------------

    public function getProductPercent()
    {
        $courses = \ProductCourse::findByProductId($this->id);
        $courseTotal = count($courses);

        $completedTotal = \UserAction::sum([
            'column'     => 'value',
            'conditions' => 'action = :action:
                AND user_id = :user_id:
                AND product_id = :product_id:',
            "bind"       => [
                'product_id' => $this->id,
                'user_id'    => $this->session->get('id'),
                'action'     => 'hasCompleted',
            ],
        ]);

        if ($completedTotal == 0) {
            return 0;
        }

        return (int)(($completedTotal / $courseTotal) * 100);
    }

    public function getDifficulty()
    {
        $empty_stars = 5 - $this->difficulty;
        $full_stars = 5 - $empty_stars;

        $output = '';

        for ($i = 0; $i < $full_stars; $i++) {
            $output .= '<i class="fa fa-circle star-rating"></i> ';
        }

        for ($i = 0; $i < $empty_stars; $i++) {
            $output .= '<i class="fa fa-circle-o star-rating-disabled"></i> ';
        }

        return $output;
    }

    // -----------------------------------------------------------------------------

}

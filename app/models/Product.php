<?php
declare(strict_types=1);

use Phalcon\Mvc\Model\Behavior\SoftDelete;
use Phalcon\Validation;
use Phalcon\Validation\Validator;

class Product extends BaseModel
{

    /**
     * @var const Project Constants
     */
    const STATUS_PLANNED = 'planned';
    const STATUS_DEVELOPMENT = 'development';
    const STATUS_PUBLISHED = 'published';

    /**
     * @var Table Rows
     */
    public $id;
    public $slug;
    public $type;
    public $title;
    public $description;
    public $tags;
    public $difficulty;
    public $duration;
    public $img_sm;
    public $img_md;
    public $img_lg;
    public $path;
    public $price;
    public $status;
    public $is_free;

    // -----------------------------------------------------------------------------

    /**
     * Phalcons std initializer when model is ready
     *
     * @return void
     */
    public function initialize() : void
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

    public function getTags() : array
    {
        return explode(',', $this->tags);
    }

    // -----------------------------------------------------------------------------

    /**
     * @return string
     */
    public function getDuration() : string
    {
        // Seconds (no float)
        $this->duration = (int) $this->duration;

        // No Time
        if ($this->duration === 0) {
            return 'n/a';
        }

        // @formula eg: 5.5
        // hours = floor(5.5)
        // minutes = subtract decimal(5.5) - hours(5) to get (.5),
        //           multiply (.5) * 60 (seconds) to get minutes.
        $hours_decimal = $this->duration / 3600;
        $hours = (int) floor($hours_decimal);
        $mins = (int) floor($hours_decimal - $hours / 60);

        $hours_str = ($hours > 1 ) ? "$hours<em>Hrs</em>" : "$hours<em>Hr</em>";
        $mins_str = ($mins > 1) ? "$mins<em>Minutes</em>" : "$mins<em>Minute</em>";
        $hours_str = sprintf("<span class='duration'>%s</span>", $hours_str);
        $mins_str = sprintf("<span class='duration'>%s</span>", $mins_str);
        if ($hours == 0) {
            return sprintf('%s', $mins_str);
        }
        return sprintf('%s %s', $hours_str, $mins_str);
    }

    // -----------------------------------------------------------------------------

    /**
     * Has a user Purchased this product?
     *
     * @param bool|int $userId Default is the user ID
     * @param bool|int $productID Default is the query made, otherwwise call manually
     * @return bool
     */
    public function hasPurchased($userId = false, $productId = false)
    {
        // Admin can go anywhere
        // if ($this->session->role === 'admin') {
        //     return true;
        // }

        if ($userId === false) {
            $userId = $this->session->get('id');
        }

        $checkId = ($productId) ?: $this->id;
        $userPurchase = \UserPurchase::findFirst([
            'product_id = :pid: AND user_id = :id:',
            'bind' => [
                'pid' => $checkId,
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

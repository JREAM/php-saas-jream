<?php
declare(strict_types=1);

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Product extends BaseModel
{

    /** @const SOURCE the table name */
    const SOURCE = 'product';

    /** @var array Saves on Memcached Queries */
    public static $_cache;

    const STATUS_PLANNED = 'planned';
    const STATUS_DEVELOPMENT = 'development';
    const STATUS_PUBLISHED = 'published';

    // --------------------------------------------------------------

    public function initialize()
    {
        $this->addBehavior(new SoftDelete([
            'field' => 'is_deleted',
            'value' => 1,
        ]));

        $this->hasMany('id', 'UserPurchase', 'product_id');
        $this->hasMany('id', 'ProductCourse', 'product_id');

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

    public function getTags()
    {
        return explode(',', $this->tags);
    }

    // --------------------------------------------------------------

    /**
     * Returns a list of Products in groups of tags
     *
     * @return [type] [description]
     */
    public static function getAllByTags()
    {
        // \Product::find(["is_deleted = 0"]);
        // $tags = \Product::find(["is_deleted = 0"]);
        $products = \Product::find([
            'conditions' => 'is_deleted = 0',
            'columns'    => 'id, title, slug, tags, img_sm, img_md',
        ])
        ->toArray();

        $tag_list = [];
        foreach ($products as $product) {
            if (!$product['tags']) {
                continue;
            }
            $tags = explode(',', $product['tags']);
            foreach ($tags as $tag) {
                if (!isset($tag_list[$tag])) {
                    $tag_list[$tag] = [];
                }
                $tag_list[$tag][] = (object)$product;
            }
        }

        // echo '<pre>';
        // print_r($tag_list);
        // echo '<hr>';
        // die;

        return $tag_list;
    }

    // --------------------------------------------------------------

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

    // --------------------------------------------------------------

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



}

<?php
declare(strict_types=1);

namespace Controllers;
use Phalcon\Mvc\View;
use Phalcon\Http\Response;

use Phalcon\Tag;

class PromotionController extends BaseController
{

    /**
     * @return void
     */
    public function onConstruct()
    {
        parent::initialize();
        Tag::setTitle('Promotions | ' . $this->di['config']['title']);
    }

    // -----------------------------------------------------------------------------

    /**
     * @return void
     */
    public function indexAction() : void
    {
        // Is a Promotion Enabled?
        $has_promotion = true;

        $promotions = \Promotion::find([
            'deleted_at IS NULL
            AND user_id IS NULL
            AND NOW() < expires_at
            AND use_count <= use_limit
            ORDER BY expires_at ASC'
        ]);

        // echo $promotions->count() ;
        // die;
        // If there are no promotions
        if ($promotions->count() < 1) {
            $has_promotion = false;
        } else {
            // $promotion = $promotion->filter(function($promo) {
            //     if (is_int($promo->use_limit) && $promo->use_count >= $promot->use_limit) {
            //         return false;
            //     }
            //     return $promo;
            // });
        }

        // echo '<pre>';
        // print_r($promotions->toArray());
        // die;

        // List All Courses
        $products = \Product::find(['is_deleted = 0 ORDER BY status DESC']);

        // if ($promotion)

        $this->view->setVars([
            'has_promotion' => $has_promotion,
            'promotions'    => $promotions,
            'products'      => $products,
        ]);

        $this->view->pick('promotion/promotion');
    }

    public function selectItemAction() : void
    {
        $this->view->disable();

        // Array of Items
        $items = $this->input->post('item');
    }

}

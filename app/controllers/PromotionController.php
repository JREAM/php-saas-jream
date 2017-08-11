<?php

use \Phalcon\Tag;

class PromotionController extends \BaseController
{

    /**
     * @return void
     */
    public function onConstruct()
    {
        parent::initialize();
        Tag::setTitle('Promotions');
    }

    // --------------------------------------------------------------

    /**
     * @return void
     */
    public function indexAction()
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
        }
        else {
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
            'tokenKey'      => $this->security->getTokenKey(),
            'token'         => $this->security->getToken(),
        ]);

        $this->view->pick('promotion/promotion');
    }

    // --------------------------------------------------------------

    public function viewAction($promotionId)
    {
        $promotion = \Promotion::findFirst(['is_delete = 0 AND NOW() < expires_at AND id = :id:'], [
            'id' => $promotionId,
        ]);
        if (!$promotion) {
            $this->view->pick(404);
        }

        $this->view->setVars([
            'has_promotion' => $has_promotion,
            'promotion'     => $promotion,
            'products'      => $products,
            'tokenKey'      => $this->security->getTokenKey(),
            'token'         => $this->security->getToken(),
        ]);
        $this->view->pick('promotion/view');
    }

    // --------------------------------------------------------------

    public function selectItemAction()
    {
        $this->view->disable();

        // Array of Items
        $items = $this->input->post('item');

    }

}

// End of File
// --------------------------------------------------------------

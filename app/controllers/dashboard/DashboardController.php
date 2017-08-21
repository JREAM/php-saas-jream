<?php

namespace Dashboard;

use \Phalcon\Tag;

/**
 * @RoutePrefix("/dashboard")
 */
class DashboardController extends \BaseController
{

    /**
     * @return void
     */
    public function onConstruct()
    {
        parent::initialize();
        Tag::setTitle('Dashboard | ' . $this->di['config']['title']);
    }

    // --------------------------------------------------------------

    /**
     * @return void
     */
    public function indexAction()
    {
        $userPurchases = \UserPurchase::findByUserId($this->session->get('id'));

        $productStatus = [];
        $purchaseIds = [];

        foreach ($userPurchases as $purchase) {
            $purchaseIds[] = $purchase->product_id;
            $product = \Product::findFirstById($purchase->product_id);

            $productStatus[$purchase->product_id] = $product->getProductPercent();
        }
        $purchaseIds = implode(',', $purchaseIds);

        $user = \User::findFirstById($this->session->get('id'));

        if ($purchaseIds) {
            // Note: Binding does not work here because
            // the imploded data renders as one string
            $products = \Product::find([
                "id NOT IN ($purchaseIds) AND is_deleted = 0 ORDER BY status DESC",
            ]);
        } else {
            $products = \Product::find();
        }

        $this->view->setVars([
            'user'          => $user,
            'products'      => $products,
            'youtube'       => \Youtube::find(),
            'productStatus' => $productStatus,
            'userPurchases' => $userPurchases,
            'hasPurchase'   => (count($userPurchases)) ? true : false,
        ]);


        $this->view->pick("dashboard/dashboard");
    }

    // --------------------------------------------------------------
}

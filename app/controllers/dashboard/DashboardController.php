<?php

namespace Controllers\Dashboard;

use Phalcon\Tag;
use Phalcon\Mvc\View;
use Phalcon\Http\Response;
use Controllers\BaseController;

class DashboardController extends BaseController
{

    /**
     * @return void
     */
    public function onConstruct(): void
    {
        parent::initialize();
        Tag::setTitle('Dashboard | ' . $this->di[ 'config' ][ 'title' ]);
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * @return View
     */
    public function indexAction(): View
    {
        $userPurchases = \UserPurchase::findByUserId($this->session->get('id'));

        $productStatus = [];
        $purchaseIds   = [];

        foreach ((object) $userPurchases as $purchase) {
            $purchaseIds[] = $purchase->product_id;
            $product       = \Product::findFirstById($purchase->product_id);

            $productStatus[ $purchase->product_id ] = $product->getProductPercent();
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
            'productStatus' => $productStatus,
            'userPurchases' => $userPurchases,
            'hasPurchase'   => (count($userPurchases)) ? true : false,
        ]);


        return $this->view->pick("dashboard/dashboard");
    }
}

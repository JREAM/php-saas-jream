<?php
declare(strict_types=1);

namespace Controllers;

use \Phalcon\Tag;
use \Omnipay\Omnipay;

class CheckoutController extends BaseController
{
    const REDIRECT_SUCCESS = 'checkout/';
    const REDIRECT_FAILURE = 'checkout/';

    public $promotion_code = null;

    // -----------------------------------------------------------------------------

    /**
     * @return void
     */
    public function onConstruct()
    {
        parent::initialize();
        Tag::setTitle('Checkout | ' . $this->di['config']['title']);

        // Stripe
        \Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET'));

        // Paypal Express
        $this->paypal = $this->di->get('paypal');
    }

    // -----------------------------------------------------------------------------

    /**
     * @return void
     */
    public function indexAction()
    {
        $products = \Product::find(['is_deleted = 0 ORDER BY status DESC']);

        $this->view->setVars([
            'products' => $products,
        ]);

        $this->view->pick('checkout/checkout');
    }


}

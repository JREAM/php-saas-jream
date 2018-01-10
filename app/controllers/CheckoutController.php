<?php

declare (strict_types = 1);

namespace Controllers;

use Phalcon\Http\Response;
use Phalcon\Mvc\View;
use Phalcon\Tag;
use Omnipay\Omnipay;

class CheckoutController extends BaseController
{
  const REDIRECT_SUCCESS = 'checkout/';
  const REDIRECT_FAILURE = 'checkout/';

  public $promotion_code = null;

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

  /**
   * @return void
   */
  public function onConstruct() : void
  {
    parent::initialize();
    Tag::setTitle('Checkout | ' . $this->di['config']['title']);

        // Stripe
    \Stripe\Stripe::setApiKey($di->api->stripe->secretKey);

        // Paypal Express
    $this->paypal = $this->di->get('paypal');
  }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

  /**
   * @return View
   */
  public function indexAction() : View
  {
    $products = \Product::find(['is_deleted = 0 ORDER BY status DESC']);

    $this->view->setVars([
      'products' => $products,
    ]);

    return $this->view->pick('checkout/checkout');
  }
}

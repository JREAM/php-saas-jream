<?php

use \Phalcon\Tag,
    \Omnipay\Omnipay;

class CheckoutController extends \BaseController
{
    const REDIRECT_SUCCESS = 'checkout/';
    const REDIRECT_FAILURE = 'checkout/';

    public $promotion_code = null;

    // --------------------------------------------------------------

    /**
     * @return void
     */
    public function onConstruct()
    {
        parent::initialize();
        Tag::setTitle('Checkout');

        // Stripe
        \Stripe\Stripe::setApiKey( getenv('STRIPE_SECRET') );

        // Paypal Express
        $this->paypal_gateway = Omnipay::create('PayPal_Express');
        $this->paypal_gateway->setUsername( getenv('PAYPAL_USERNAME') );
        $this->paypal_gateway->setPassword( getenv('PAYPAL_PASSWORD') );
        $this->paypal_gateway->setSignature( getenv('PAYPAL_SIGNATURE') );

        if ( getenv('PAYPAL_TESTMODE') ) {
            $this->paypal_gateway->setTestMode(true);
        }
    }

    // --------------------------------------------------------------

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

    // --------------------------------------------------------------

    /**
     * Stripe Payment
     *
     * @param integer $productId
     *
     * @return void
     */
    public function doStripeAction($productId)
    {
        $this->view->disable();
        $product = \Product::findFirstById($productId);
        if (!$product) {
            $this->flash->error('No product was found with the Id:' . $productId);

            return $this->redirect(self::REDIRECT_FAILURE . $product->slug);
        }

        $this->component->helper->csrf(self::REDIRECT_FAILURE . $product->slug);


        if ($product->hasPurchased() == true) {
            $this->flash->error('You have already purchased this');

            return $this->redirect(self::REDIRECT_FAILURE_UNAVAILABLE . $product->slug);
        }

        $stripeToken = $this->request->getPost('stripeToken');
        $name = $this->request->getPost('name');
        $zip = $this->request->getPost('zip');

        if (!$name) {
            $this->flash->error('You must provide a name.');

            return $this->redirect(self::REDIRECT_FAILURE . $product->slug);
        }

        // Default Discount and Price
        $discount = 0;
        $use_price = $product->price;

        // Check for discount
        if ($this->security->checkHash($this->config->hash, $this->session->getId())) {
            $discount = $this->session->get('discount');
            $use_price = $this->session->get('discount_price');
        }

        $amount = number_format($use_price, 2);

        // If a coupon is applied
        if ($this->session->has('code')) {
            $code = $this->session->get('code');
            $amount = number_format($code['price'], 2);
        }

        $amount_in_cents = (int)($amount * 100);
        $revert_money_test = money_format("%i", ($amount_in_cents / 100));

        // Ensure the amount is valid! This is paranoid but lets be safe!
        if ($amount != $revert_money_test) {
            $msg = "We are sorry, a calculation went wrong before charging your card.
                    This is a protective measure to not overcharge anyone before passing it to Stripe.
                    This is a unique error, and it is logged and emailed to JREAM.";
            $this->di->get('sentry')->captureMessage($msg, [
                'amount'                => $amount,
                'amount_after_discount' => $amount_after_discount,
                'amount_in_cents'       => $amount_in_cents,
                'revert_money_test'     => $revert_money_test,
            ]);
            $this->flash->error($msg);

            return $this->redirect(self::REDIRECT_FAILURE . $product->slug);
        }

        try {
            $response = \Stripe\Charge::create([
                'source'      => $stripeToken,
                'amount'      => $amount_in_cents,
                'currency'    => 'usd',
                'description' => $product->title,
                'metadata'    => [
                    'name'     => $name,
                    'user_id'  => $this->session->get('id'),
                    'zip'      => $zip,
                    'promo'    => $this->promotion_code,
                    'discount' => $discount,
                ],
            ]);
        } catch (\Stripe\Error\Card $e) {
            $this->di->get('sentry')->captureException($e);
            $this->flash->error($e->getMessage);

            return $this->redirect(self::REDIRECT_FAILURE . $product->slug);
        }

        // Check failure of non 200
        if ((int)$response->getLastResponse()->code != 200) {
            $msg = "Sorry, the Stripe Gateway did not return a successful response.";
            $this->di->get('sentry')->captureMessage($msg);
            $this->flash->error($msg);

            return $this->redirect(self::REDIRECT_FAILURE . $product->slug);
        }

        // Check failure of paid false
        if ((int)$response->paid == 0) {
            $this->di->get('sentry')->captureException($response);
            $this->flash->error('There was a problem processing your payment');

            return $this->redirect(self::REDIRECT_FAILURE . $product->slug);
        }

        if ((int)$response->paid == 1) {
            $this->_createPurchase($product, 'Stripe', $response->getLastResponse()->json['id']);

            return $this->redirect(self::REDIRECT_SUCCESS . $product->id);
        }

        $this->flash->error('Sorry, your Stripe API Payment was not returned as paid.');

        return $this->redirect(self::REDIRECT_FAILURE . $product->slug);
    }

    // --------------------------------------------------------------

    /**
     * Paypal Payment
     *
     * @param  integer $productId
     *
     * @return void
     */
    public function doPayPalAction($productId)
    {
        $product = \Product::findFirstById($productId);

        if (!$product) {
            $this->flash->error('No product was found with the Id:' . $productId);

            return $this->redirect(self::REDIRECT_FAILURE . $product->slug);
        }

        if ($product->hasPurchased() == true) {
            $this->flash->error('You have already purchased this');

            return $this->redirect(self::REDIRECT_FAILURE_UNAVAILABLE . $product->slug);
        }

        // Default Price
        $use_price = $product->price;

        // Check for discount
        if ($this->security->checkHash($this->config->hash, $this->session->getId())) {
            $use_price = $this->session->get('discount_price');
        }

        $amount = number_format($use_price, 2);

        // If coupon
        if ($this->session->has('code')) {
            $code = $this->session->get('code');
            $amount = number_format($code['price'], 2);
        }

        $response = $this->paypal_gateway->purchase([
            'cancelUrl'   => getBaseUrl('dashboard'),
            'returnUrl'   => getBaseUrl('product/dopaypalconfirm/' . $product->id),
            'amount'      => $amount,
            'currency'    => 'usd',
            'description' => $product->title,
        ])->send();

        $response->redirect();
    }

    // --------------------------------------------------------------

    /**
     * Paypal Confirmation after returning from payment
     *
     * @param  integer $productId
     *
     * @return void
     */
    public function doPaypalConfirmAction($productId)
    {
        $product = \Product::findFirstById($productId);
        if (!$product) {
            $this->flash->error('Could not complete your transaction. The productId is invalid.');

            return $this->redirect(self::REDIRECT_FAILURE . $product->slug);
        }

        if (!$productId || $product->hasPurchased() == true) {
            return $this->redirect(self::REDIRECT_FAILURE_UNAVAILABLE . $product->slug);
        }

        $amount = number_format($product->price, 2);

        // If coupon
        if ($this->session->has('code')) {
            $code = $this->session->get('code');
            $amount = number_format($code['price'], 2);
        }

        try {
            $response = $this->paypal_gateway->completePurchase([
                'amount'   => $amount,
                'currency' => 'USD',
            ])->send();
        } catch (\Exception $e) {
            $this->flash->error('Could not complete your transaction. Paypal has had an error.');

            return $this->redirect(self::REDIRECT_FAILURE . $product->slug);
        }

        if ($response->isSuccessful() == false) {
            $this->flash->error('There was a problem processing your payment');

            return $this->redirect(self::REDIRECT_FAILURE . $product->slug);
        }

        $data = $response->getData(); // this is the raw response object

        if (empty($data)) {
            $this->flash->error('There was no response from paypal.');

            return $this->redirect(self::REDIRECT_FAILURE . $product->slug);
        }

        if (strtolower($data['ACK']) != 'success') {
            $this->flash->error('Payment was unsuccessful.');

            return $this->redirect(self::REDIRECT_FAILURE . $product->slug);
        }

        $transactionID = false;
        if (isset($data['PAYMENTINFO_0_TRANSACTIONID'])) {
            $transactionID = $data['PAYMENTINFO_0_TRANSACTIONID'];
        }

        $this->_createPurchase($product, 'Paypal Express Checkout', $transactionID);

        return $this->redirect(self::REDIRECT_SUCCESS . $product->id);
    }

    // --------------------------------------------------------------

    /**
     * Create a Purchase Record
     *
     * @param  Product $product
     * @param  mixed   $gateway
     * @param  mixed   $transaction_id
     *
     * @return void
     */
    private function _createPurchase(\Product $product, $gateway = false, $transaction_id = false)
    {
        // First create a transaction record
        $transaction = new \Transaction();
        $transaction->user_id = $this->session->get('id');
        $transaction->transaction_id = $transaction_id;
        $transaction->type = 'purchase';
        $transaction->gateway = strtolower($gateway);

        // Default Price
        $use_price = $product->price;

        $promo_applied = false;
        // Check for discount
        if ($this->security->checkHash($this->config->hash, $this->session->getId())) {
            $use_price = $this->session->get('discount_price');
            $promo_applied = true;
        }

        $transaction->amount = $use_price;

        $purchased_for = number_format($product->price, 2);

        // If coupon
        if ($this->session->has('code')) {
            $code = $this->session->get('code');
            $transaction->amount_after_discount = number_format($code['price'], 2);
            $purchased_for = number_format($code['price'], 2);
        }

        $result = $transaction->save();


        // Insert the user record
        $userPurchase = new \UserPurchase();
        $userPurchase->user_id = $this->session->get('id');
        $userPurchase->product_id = $product->id;
        $userPurchase->transaction_id = $transaction->id;
        if ($promo_applied) {
            $userPurchase->promotion_code = $this->promotion_code;
        }
        $userPurchase->save();

        $content = $this->component->email->create('purchase', [
            'product_title'  => $product->title,
            'product_img'    => $product->img_sm,
            'login_url'      => \URL . '/user/login',
            'product_price'  => $purchased_for,
            'gateway'        => $gateway,
            'transaction_id' => $transaction_id,
        ]);

        $user = \User::findFirstById($this->session->get('id'));


        $mail_result = $this->di->get('email', [
            [
                'to_name'    => $user->getAlias(),
                'to_email'   => $user->getEmail(),
                'from_name'  => $this->config->email->from_name,
                'from_email' => $this->config->email->from_address,
                'subject'    => 'JREAM Purchase Confirmation',
                'content'    => $content,
            ],
        ]);

        if (!in_array($mail_result->statusCode(), [200, 201, 202])) {
            $this->flash->success("
            Course addition: {$product->title} was successful!
            However, there was a problem sending an email to: " . $user->getEmail() . " -
            Don't worry! The course is in your account!"
            );
        } else {
            $this->flash->success("
            Course addition: {$product->title} was successful!
            Your should receive an email confirmation shortly to: " . $user->getEmail()
            );

        }


    }

    // --------------------------------------------------------------

}

// End of File
// --------------------------------------------------------------

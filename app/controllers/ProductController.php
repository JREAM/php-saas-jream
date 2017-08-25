<?php
declare(strict_types=1);

namespace Controllers;

use \Phalcon\Tag;
use \Omnipay\Omnipay;

class ProductController extends BaseController
{
    const REDIRECT_MAIN = 'product';
    const REDIRECT_SUCCESS = 'dashboard/course/index/';
    const REDIRECT_FAILURE = 'product/course/';
    const REDIRECT_FAILURE_UNAVAILABLE = 'product/';

    public $promotion_code = null;

    // --------------------------------------------------------------

    /**
     * @return void
     */
    public function onConstruct()
    {
        parent::initialize();
        Tag::setTitle('Products | ' . $this->di['config']['title']);

        // Stripe key already set in services.php

        // Paypal Express
        $this->paypal = $this->di->get('paypal');
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
    }

    // --------------------------------------------------------------

    /**
     * Displays a Product based on the slug
     *
     * @param  string   $slug  URL Friendly Slug
     *
     * @return void
     */
    public function courseAction(string $slug)
    {
        $product = \Product::findFirstBySlug($slug);
        Tag::setTitle($product->title . ' | ' . $this->di['config']['title']);

        if (!$product) {
            return $this->redirect(self::REDIRECT_MAIN);
        }

        $discount = null;
        $discount_price = null;
        $promotion_code = $this->request->get('promotion_code');
        if ($promotion_code) {
            $promotion = new \Promotion();
            $percent_off = $promotion->check($promotion_code, $product->id);
            if ($percent_off->code !== 0) {
                // Keep the promo on the users incase they refresh the page
                $this->promotion_code = $promotion_code;

                // this sets the price discount
                // Security
                $this->session->set('session_hash', $this->security->hash(
                    $this->config->session_hash,
                    $this->session->getId()
                ));

                // Price
                $discount_price = $product->price * ($percent_off * .1);
                $this->session->set('discount', $percent_off);
                $this->session->set('discount_price', $discount_price);
            }
        }

        $courses = \ProductCourse::find([
            "product_id = :product_id:",
            "bind"  => [
                'product_id' => $product->id,
            ],
            "order" => 'section, course',
        ]);

        // ---------------------------
        // Facebook Login
        // ---------------------------
        $after_fb = sprintf('/product/course/%s', $slug);
        $helper = $this->facebook->getRedirectLoginHelper();
        $fbLoginUrl = $helper->getLoginUrl(
            $this->api->fb->redirectUri,
            (array) $this->api->fb->scope
        );

        // ---------------------------
        // End Facebook
        // ---------------------------

        $user = false;
        if ($this->session->has('id')) {
            $user = \User::findFirstById($this->session->get('id'));
        }

        $months = [
            1  => 'January',
            2  => 'February',
            3  => 'March',
            4  => 'April',
            5  => 'May',
            6  => 'June',
            7  => 'July',
            8  => 'August',
            9  => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December',
        ];

        $this->view->setVars([
            'user'           => $user,
            'product'        => $product,
            'courses'        => $courses,
            'promotion_code' => $promotion_code,
            'discount_price' => $discount_price,
            'hasPurchased'   => $product->hasPurchased(),
            'fbLoginUrl'     => $fbLoginUrl,
            'months'         => $months,
            'years'          => range(date('Y'), date('Y') + 5),
        ]);
    }

    // --------------------------------------------------------------

    private function doPromotionAction()
    {
        // USES The ApiController (Because it is used on the Promotions page as well).
    }

    // --------------------------------------------------------------

    /**
     * Preview a Course
     *
     * @param string $productSlug
     * @param int    $courseId
     */
    public function coursePreviewAction(string $productSlug, integer $courseId)
    {
        $rtmpSignedUrl = null;
        $error = null;

        $product = \Product::findFirstBySlug($productSlug);
        $productCourse = \ProductCourse::findFirstById($courseId);
        if (!$product || !$productCourse) {
            $this->flash->error('This product and/or course does not exist');

            return $this->redirect(self::REDIRECT_MAIN);
        }

        if ($productCourse->free_preview == 1) {
            $rtmpSignedUrl = \ProductCourse::generateStreamUrl(
                $productCourse->getProduct()->path,
                $productCourse->name
            );
        } else {
            $error = 'There is no preview for this course, please purchase at the product area.';
        }

        Tag::setTitle(formatName($productCourse->name) . ' | Product Preview | ' . $this->di['config']['title']);

        $this->view->setVars([
            'rtmpSignedUrl' => $rtmpSignedUrl,
            'error'         => $error,
            'product'       => $product,
            'productCourse' => $productCourse,
            'courseName'    => formatName($productCourse->name),
        ]);

        $this->view->pick('product/preview');
    }

    // --------------------------------------------------------------

    /**
     * Stripe Payment
     *
     * @param integer $productId
     *
     * @return void
     */
    public function doStripeAction(integer $productId)
    {
        $this->view->disable();
        $this->component->helper->csrf(self::REDIRECT_MAIN);

        // @TODO Future stuff to reduce code
//        $product = new \Product();
//        $result = $product->doPurchase($productId);
//        if ($result->code === 0) {
//            $this->flash->error($result->error);
//            return $this->redirect(REDIRECT_MAIN);
//        }

        $product = \Product::findFirstById($productId);
        if (!$product) {
            $this->flash->error('No product was found with the Id: %s', $productId);

            return $this->redirect(self::REDIRECT_MAIN);
        }

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

        // Checks for Promotion Applied.
        // Successful promotions create a cookie (In-case they login/logout)
        $promo = $this->components->cookie->get('promotion');
        if ($promo) {
            $promo = json_decode('promotion');

            // The USER ID (If Set) Is checked when the Cookie is created, it won't get to this
            // point, or shouldn't -- but I'll double protect anyways.
            if ($promo->user_id && $this->session->get('id') != $promo->user_id) {
                $this->flash->error('This promotion is for an individual only, it does not appear to be you, sorry.');

                return $this->redirect(self::REDIRECT_FAILURE . $product->slug);
            }

            // If ProductID is set, ensure they are applying correctly
            if ($promo->product_id && $product->id != $promo->product_id) {
                $other_product = \Product::getById($promo->product_id);
                $this->flash->error('You provided a promotion to the wrong course, this applies to: ' . $other_product->title);

                return $this->redirect(self::REDIRECT_FAILURE . $product->slug);
            }

            // Make sure to check this DURING the checkout
            // @TODO: Use UTC Timestamp
//            if ($promo->expires_at > $this->helper->getLocaleTimestamp()) {
//                $this->flash->error('Sorry, this promotion expired on ' . $promo->expires_at);
//
//                return $this->redirect(self::REDIRECT_FAILURE . $product->slug);
//            }

            // Make sure to check this DURING the checkout
            if ($promo->deleted_at) {
                $this->flash->error('Sorry, this promotion was deleted on ' . $promo->deleted_at);

                return $this->redirect(self::REDIRECT_FAILURE . $product->slug);
            }

            // Only one of these apply
            if ($promo->percent_off) {
                $promo_method = 'percent_off';
                $promo = sprintf(
                    "Price marked down from %s to %s at %s percent off using promotional code %s.",
                    number_format($product->price - ($product->price * $promo->percent_off), 2),
                    $promo->percent_off,
                    $promo->code
                );
                $use_price = number_format($product->price - ($product->price * $promo->percent_off), 2);
            } elseif ($promo->price) {
                $promo_method = 'price';
                $promo = sprintf(
                    "Price marked down from %s to %s using promotional code %s.",
                    number_format($product->price, 2),
                    number_format($promo['price'], 2),
                    $promo->code
                );

                $use_price = $promo->price;
            }
        }

        $discount = 0;
        $use_price = $product->price;

        $promotion = $this->validatePromotionCode($productId);
        if ($promotion) {
            // use tihs or that... @TODO
        }
        $amount = number_format($use_price, 2);

        // If a coupon is applied
        // if ($this->session->has('code')) {
        //     $code = $this->session->get('code');
        //     $amount = number_format($code['price'], 2);
        // }

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
    public function doPayPalAction(integer $productId)
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

        $response = $this->paypal->purchase([
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
    public function doPaypalConfirmAction(integer $productId)
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
            $response = $this->paypal->completePurchase([
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
     * Add a free course (No Purchase Required)
     *
     * @param  integer $productId
     *
     * @return void
     */
    public function doFreeCourseAction(integer $productId)
    {
        $product = \Product::findFirstById($productId);
        if (!$product || $product->price != 0) {
            $this->flash->error('Sorry this is an invalid or non-free course.');

            return $this->redirect(self::REDIRECT_SUCCESS . $product->id);
        }

        $this->_createPurchase($product, 'free', 'website');

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
            Don't worry! The course is in your account!");
        } else {
            $this->flash->success("
            Course addition: {$product->title} was successful!
            Your should receive an email confirmation shortly to: " . $user->getEmail());
        }
    }

    // --------------------------------------------------------------
}

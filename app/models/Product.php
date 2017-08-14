<?php

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
        $this->hasMany('id', 'ProductCategory', 'product_id');

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

    // --------------------------------------------------------------

    /**
     * @param $productId
     * @TODO Not implemented
     * @return object
     */
    public function doPurchase($productId)
    {
        // Ensure Exists
        $product = \Product::findFirstById($productId);
        if (!$product) {
            return (object) [
                'code' => 0,
                'data' => null,
                'success' => '',
                'error' => sprintf('No product was found with the Id: %s', $productId)
            ];
        }

        // Ensure not already Purchased
        if ($product->hasPurchased() == true) {
            return (object) [
                'code' => 0,
                'data' => $product,
                'success' => '',
                'error' => sprintf('You have already purchased %s.', $product->title)
            ];
        }

        // Checks for Promotion Applied.
        // Successful promotions create a cookie (In-case they login/logout)
        $promo_code = $this->components->cookie->get('promotion');
        if ($promo_code) {
            $promotion = new Promotion();
            $promotion_result = $promotion->check($promo_code); // false or SimpleResult
            if ($promotion_result->code === 0) {
                $promotion_result->error;
            }
        }


    }

    // --------------------------------------------------------------

    protected function _stripe()
    {
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

    protected function _paypal()
    {
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

    protected function _paypalConfirm()
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

    protected function _createPurchase()
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
}

// End of File
// --------------------------------------------------------------

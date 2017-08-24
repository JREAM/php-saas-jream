<?php
declare(strict_types=1);

namespace Controllers\Api;

use \User;
use \UserPurchase;
use \Promotion;
use \Transaction;
use \Product;

/**
 * @RoutePrefix("/api/purchase")
 */
class PurchaseController extends ApiController
{

    /**
     * @return void
     */
    public function onConstruct()
    {
        parent::initialize();
    }

    // --------------------------------------------------------------

    /**
     * @return string JSON
     */
    public function applyPromotionAction()
    {
        $code = $this->input->getPost('code');
        $productId = $this->input->getPost('productId');

        $user_id = $this->session->get('user_id');

        $promotion = new Promotion();
        $result = $promotion->check($code, $productId);
    }

    /**
     * @param int   $productId
     * @return string JSON
     */
    public function freeAction(int $productId)
    {
        $product = \Product::findFirstById($productId);
        if (!$product || $product->price != 0) {
            return $this->output(0, 'Sorry this is an invalid or non-free course.');
        }

        $do = $this->_createPurchase($product, 'free', 'website');
        if ($do->result) {
            return $this->output(1, ['redirect' => $product->id]);
        }

        return $this->output(0, $do->msg);
    }

    /**
     * @return string   JSON
     */
    public function stripeAction($productId)
    {
        $product = \Product::findFirstById($productId);
        if (!$product) {
            return $this->output(0, 'No product was found with the Id: %s', $productId);
        }

        if ($product->hasPurchased() == true) {
            return $this->output->response(0, 'You have already purchased this');
        }

        $stripeToken = $this->request->getPost('stripeToken');
        $name = $this->request->getPost('name');
        $zip = $this->request->getPost('zip');

        if (!$name) {
            return $this->output(0, 'You must provide a name.');
        }

        // Checks for Promotion Applied.
        // Successful promotions create a cookie (In-case they login/logout)
        $promo = $this->components->cookie->get('promotion');

        if ($promo) {
            $promo = json_decode('promotion');

            // The USER ID (If Set) Is checked when the Cookie is created, it won't get to this
            // point, or shouldn't -- but I'll double protect anyways.
            if ($promo->user_id && $this->session->get('id') != $promo->user_id) {
                return $this->output(0, 'This promotion is for an individual only, it does not appear to be you, sorry.');
            }

            // If ProductID is set, ensure they are applying correctly
            if ($promo->product_id && $product->id != $promo->product_id) {
                $other_product = \Product::getById($promo->product_id);

                return $this->output(0, 'You provided a promotion to the wrong course, this applies to: ' . $other_product->title);
            }

            // Make sure to check this DURING the checkout
            if ($promo->expires_at > $this->helper->getLocaleTimestamp()) {
                return $this->output(0, 'Sorry, this promotion expired on ' . $promo->expires_at);
            }

            // Make sure to check this DURING the checkout
            if ($promo->deleted_at) {
                return $this->output(0, 'Sorry, this promotion was deleted on ' . $promo->deleted_at);
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
                'amount_after_discount' => 1,//$amount_after_discount,
                'amount_in_cents'       => $amount_in_cents,
                'revert_money_test'     => $revert_money_test,
            ]);

            return $this->output(0, $msg);
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

            return $this->output(0, $e->getMessage());
        }

        // Check failure of non 200
        if ((int)$response->getLastResponse()->code != 200) {
            $msg = "Sorry, the Stripe Gateway did not return a successful response.";
            $this->di->get('sentry')->captureMessage($msg);

            return $this->output(0, $msg);
        }

        // Check failure of paid false
        if ((int)$response->paid == 0) {
            $this->di->get('sentry')->captureException($response);

            return $this->output(0, 'There was a problem processing your payment');
        }

        if ((int)$response->paid == 1) {
            $this->_createPurchase($product, 'Stripe', $response->getLastResponse()->json['id']);

            return $this->output(1, 'Success.');
        }

        return $this->output(0, 'Sorry, your Stripe API Payment was not returned as paid.');
    }

    /**
     * @param int   $productId
     *
     * @return string   JSON
     */
    public function paypalAction(int $productId)
    {
        $product = \Product::findFirstById($productId);

        if (!$product) {
            return $this->output(0, 'No product was found with the Id:' . $productId);
        }

        if ($product->hasPurchased() == true) {
            return $this->output(0, 'You have already purchased this');
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

        // How to handle this? @TODO
        $response->redirect();
    }

    /**
     * @TODO If I change this make sure i change in paypal if i need to
     *
     * @param  integer  $productId
     * @return string   JSON
     */
    public function doPaypalConfirmAction(int $productId)
    {
        $product = \Product::findFirstById($productId);
        if (!$product) {
            return $this->output(0, 'Could not complete your transaction. The productId is invalid.');
        }

        if (!$productId || $product->hasPurchased() == true) {
            return $this->output(0, 'Product does not exist or has been purchased.');
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
            return $this->output(0, 'Could not complete your transaction. Paypal has had an error.');
        }

        if ($response->isSuccessful() == false) {
            return $this->output(0, 'There was a problem processing your payment');
        }

        $data = $response->getData(); // this is the raw response object

        if (empty($data)) {
            return $this->output(0, 'There was no response from paypal.');
        }

        if (strtolower($data['ACK']) != 'success') {
            return $this->output(0, 'Payment was unsuccessful.');
        }

        $transactionID = false;

        if (isset($data['PAYMENTINFO_0_TRANSACTIONID'])) {
            $transactionID = $data['PAYMENTINFO_0_TRANSACTIONID'];
        }

        $do = $this->_createPurchase($product, 'Paypal Express Checkout', $transactionID);

        if (!$do->result) {
            return $this->output(0, $do->msg);
        }

        return $this->output(1, ['redirect' => $product->id]);
    }

    // --------------------------------------------------------------

    /**
     * Create a Purchase Record
     *
     * @param  \Product $product
     * @param  mixed   $gateway
     * @param  mixed   $transaction_id
     *
     * @return object
     */
    private function _createPurchase(Product $product, $gateway = false, $transaction_id = false)
    {
        // First create a transaction record
        $transaction = new Transaction();
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

        $transaction->save();


        // Insert the user record
        $userPurchase = new UserPurchase();
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

        $user = User::findFirstById($this->session->get('id'));


        // Send email regarding purchase
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
            return (object) [
                'result' => 0,
                'msg' => "Course addition: {$product->title} was successful!
                    However, there was a problem sending an email to: " . $user->getEmail() . " -
                    Don't worry! The course is in your account!"
            ];
        }

        return (object) [
            'result' => 1,
            'msg' => "Course addition: {$product->title} was successful!
            Your should receive an email confirmation shortly to: \" . $user->getEmail());"
        ];
    }
}

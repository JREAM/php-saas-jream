<?php

declare(strict_types=1);

namespace Controllers\Api;

use Phalcon\Http\Response;
use User;
use UserPurchase;
use Promotion;
use Product;

class PurchaseController extends ApiController
{

    const PROMO_KEY = 'promo_code';

    public function onConstruct()
    {
        parent::initialize();
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * Applies promotional code
     * @method POST
     *         code <string>
     *         productId <int>
     * @return Response
     */
    public function applyPromotionAction(): Response
    {
        $this->apiMethods(['POST']);
        $this->json = $this->request->getJsonRawBody();

        $promoCode = $this->json->promo_code;
        $productId = $this->json->product_id;

        $promotion = new Promotion();
        $promotionResult = $promotion->check($promoCode, $productId);

        if ($promotionResult) {
            return $this->output(0, 'Sorry, Promotion code was not found.', ['promo_code' => $promoCode]);
        }

        $this->session->set('promo_code', $promoCode);
        return $this->output(1, 'Promotion Applied.', ['promo_code' => $promoCode]);
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * Adds free product
     *
     * @method POST
     *         productId <int>
     *
     * @return Response
     */
    public function freeAction(): Response
    {
        $this->apiMethods(['POST']);

        $productId = $this->json->product_id;

        $product = \Product::findFirstById($productId);
        if (!$product || $product->price != 0) {
            return $this->output(0, 'Sorry this is an invalid or non-free course.');
        }

        $create = $this->createPurchase($product, 'free', 'website');
        if ($create->result) {
            return $this->output(1, 'Free course added.', ['free_course' => $product->title, 'id' => $productId]);
        }

        return $this->output(0, $create->msg);
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * Handles Stripe Payment Gateway
     * @method POST
     *         productId <int>
     *         stripeToken <string>
     *         name <string>
     *         zip <string>
      *        @TODO This was not handling promotion
     * @return Response
     */
    public function stripeAction(): Response
    {
        $this->apiMethods(['POST']);

        $productId = (int) $this->request->getPost('product_id');

        $product = \Product::findFirstById($productId);
        if (!$product) {
            return $this->output(0, 'No product was found with the Id: %s', $productId);
        }

        if ($product->hasPurchased() == true) {
            return $this->output(0, 'You have already purchased this');
        }

        $stripeToken = $this->request->getPost('stripeToken');
        $name        = $this->request->getPost('name');
        $zip         = $this->request->getPost('zip');

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
                $otherProduct = \Product::getById($promo->product_id);

                return $this->output(0, 'You provided a promotion to the wrong course, this applies to: ' .
                                        $otherProduct->title);
            }

            // Make sure to check this DURING the checkout
            if ($promo->expires_at > \User::getLocaleTimestamp()) {
                return $this->output(0, 'Sorry, this promotion expired on ' . $promo->expires_at);
            }

            // Make sure to check this DURING the checkout
            if ($promo->deleted_at) {
                return $this->output(0, 'Sorry, this promotion was deleted on ' . $promo->deleted_at);
            }

            // Only one of these apply
            if ($promo->percent_off) {
                $promo_method = 'percent_off';
                $promo        = sprintf("Price marked down from %s to %s at %s percent off using promotional code %s.", $product->price, number_format($product->price -
                                                                                                                                                       ($product->price *
                                                                                                                                                        $promo->percent_off), 2), $promo->percent_off, $promo->code);
                $usePrice    = number_format($product->price - ($product->price * $promo->percent_off), 2);
            } elseif ($promo->price) {
                $promo_method = 'price';
                $promo        = sprintf("Price marked down from %s to %s using promotional code %s.", number_format($product->price, 2), number_format($promo[ 'price' ], 2), $promo->code);

                $usePrice = $promo->price;
            }
        }

        $discount  = 0;
        $usePrice = $product->price;

        $promotion = $this->validatePromotionCode($productId);
        if ($promotion) {
            // use tihs or that... @TODO
        }
        $amount = number_format($usePrice, 2);

        // If a coupon is applied
        // if ($this->session->has('code')) {
        //     $code = $this->session->get('code');
        //     $amount = number_format($code['price'], 2);
        // }

        $amountInCents   = (int) ($amount * 100);
        $revertMoneyTest = money_format("%i", ($amountInCents / 100));

        // Ensure the amount is valid! This is paranoid but lets be safe!
        if ($amount != $revertMoneyTest) {
            $msg = "We are sorry, a calculation went wrong before charging your card.
                    This is a protective measure to not overcharge anyone before passing it to Stripe.
                    This is a unique error, and it is logged and emailed to JREAM.";
            $this->di->get('sentry')->captureMessage($msg, [
                'amount'                => $amount,
                'amount_after_discount' => 1,//$amount_after_discount,
                'amount_in_cents'       => $amountInCents,
                'revert_money_test'     => $revertMoneyTest,
            ]);

            return $this->output(0, $msg);
        }

        try {
            $response = \Stripe\Charge::create([
                'source'      => $stripeToken,
                'amount'      => $amountInCents,
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
        if ((int) $response->getLastResponse()->code != 200) {
            $msg = "Sorry, the Stripe Gateway did not return a successful response.";
            $this->di->get('sentry')->captureMessage($msg);

            return $this->output(0, $msg);
        }

        // Check failure of paid false
        if ((int) $response->paid == 0) {
            $this->di->get('sentry')->captureException($response);

            return $this->output(0, 'There was a problem processing your payment');
        }

        if ((int) $response->paid == 1) {
            $this->createPurchase($product, 'Stripe', $response->getLastResponse()->json[ 'id' ]);

            return $this->output(1, 'Success.');
        }

        return $this->output(0, 'Sorry, your Stripe API Payment was not returned as paid.');
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * Handles PayPal Payment Gateway
     * Redirects to PayPal Checkout and Back to Confirm in "paypalConfirm"
     * @method GET
     *         code <string>
     *         productId <int>
     * @param int $productId
     *
     * @return Response
     */
    public function paypalAction(int $productId): Response
    {
        $this->apiMethods(['GET']);

        $product = \Product::findFirstById($productId);

        if (!$product) {
            return $this->output(0, 'No product was found with the Id:' . $productId);
        }

        if ($product->hasPurchased() == true) {
            return $this->output(0, 'You have already purchased this');
        }

        // Default Price
        $usePrice = $product->price;

        // Check for discount
        if ($this->security->checkHash($this->config->hash, $this->session->getId())) {
            $usePrice = $this->session->get('discount_price');
        }

        $amount = number_format($usePrice, 2);

        // If coupon
        if ($this->session->has(self::PROMO_KEY)) {
            $code = $this->session->get(self::PROMO_KEY);
            $amount = number_format($code['price'], 2);
        }

        $response = $this->paypal->purchase([
            'cancelUrl'   => \Library\Url::get('dashboard'),
            'returnUrl'   => \Library\Url::get("api/purchase/paypalconfirm/{$product->id}"),
            'amount'      => $amount,
            'currency'    => $this->config->currency,
            'description' => $product->title,
        ])->send();

        // How to handle this? @TODO
        return $this->output(1, 'redirecting to paypal', ['redirect' => $response->redirect()]);
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * @TODO If I change this make sure i change in paypal if i need to
     *
     * @param  int $productId
     *
     * @return Response
     */
    public function paypalConfirmAction(int $productId): Response
    {
        $this->apiMethods(['GET']);

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
            $code   = $this->session->get('code');
            $amount = number_format($code[ 'price' ], 2);
        }

        try {
            $response = $this->paypal->completePurchase([
                'amount'   => $amount,
                'currency' => $this->config->currency,
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

        if (strtolower($data[ 'ACK' ]) != 'success') {
            return $this->output(0, 'Payment was unsuccessful.');
        }

        $transactionId = false;

        if (isset($data[ 'PAYMENTINFO_0_TRANSACTIONID' ])) {
            $transactionId = $data[ 'PAYMENTINFO_0_TRANSACTIONID' ];
        }

        $do = $this->createPurchase($product, 'Paypal Express Checkout', $transactionId);

        if (!$do->result) {
            return $this->output(0, $do->msg);
        }

        return $this->output(1, null, ['redirect' => $product->id]);
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * Create a Purchase Record
     *
     * @param  \Product $product
     * @param  mixed    $gateway$usePrice
     * @param  mixed    $transactionId
     *
     * @return object
     */
    private function createPurchase(Product $product, $gateway = false, $transactionId = false)
    {
        // First create a transaction record
        //$transaction                 = new Transaction();
        //$transaction->user_id        = $this->session->get('id');

        $userPurchase = new UserPurchase();
        $userPurchase->user_id = $this->session->get('id');
        $userPurchase->product_id = 1;
        $userPurchase->promotion_id = 1; // Code is stored in the promotion table
        $userPurchase->type = 'purchase';

        // Gateway Related
        $userPurchase->transaction_id = $transactionId;
        $userPurchase->gateway = strtolower($gateway);

        // Calculate the Amount Below

        // Default Price
        $usePrice = $product->price;

        $wasPromoApplied = false;

        // Check for discount
        if ($this->security->checkHash($this->config->session_hash, $this->session->getId())) {
            $usePrice     = $this->session->get('discount_price');
            $wasPromoApplied = true;
        }

        // Set the default Price
        $userPurchase->amount = $usePrice;

        $purchasedFor = number_format((float) $product->price, 2);

        // If coupon
        if ($this->session->has('code')) {
            $code                               = $this->session->get('code');
            $userPurchase->amount_after_discount = number_format($code[ 'price' ], 2);
            $purchasedFor = $userPurchase->amount_after_discount;
        }

        $userPurchase->save();

        // Insert the user record
        $userPurchase                 = new UserPurchase();
        $userPurchase->user_id        = $this->session->get('id');
        $userPurchase->product_id     = $product->id;

        if ($wasPromoApplied) {
            $userPurchase->promotion_code = $this->promotion_code;
        }
        $userPurchase->save();

        //$content = $this->component->email->create('purchase', [
        //    'product_title'  => $product->title,
        //    'product_img'    => $product->img_sm,
        //    'login_url'      => \URL . '/user/login',
        //    'product_price'  => $purchasedFor,
        //    'gateway'        => $gateway,
        //    'transaction_id' => $transactionId,
        //]);

        $user = User::findFirstById($this->session->get('id'));
        $userEmail = $user->getEmail();

        // @TODO FIX THIS LATER
        //// Send email regarding purchase
        //$mail_result = $this->di->get('email', [
        //    [
        //        'to_name'    => $user->getAlias(),
        //        'to_email'   => $user->getEmail(),
        //        'from_name'  => $this->config->email->from_name,
        //        'from_email' => $this->config->email->from_address,
        //        'subject'    => 'JREAM - Purchase Confirmation',
        //        'content'    => $content,
        //    ],
        //]);
        //
        //if (!in_array($mail_result->statusCode(), [200, 201, 202])) {
        //    return (object) [
        //        'result' => 0,
        //        'msg'    => "Course addition: {$product->title} was successful!
        //            However, there was a problem sending an email to: " . $user->getEmail() . " -
        //            Don't worry! The course is in your account!",
        //    ];
        //}

        return (object) [
            'result' => 1,
            'msg'    => "Course addition: {$product->title} was successful!
            Your should receive an email confirmation shortly to: \" . $userEmail);",
        ];
    }
}

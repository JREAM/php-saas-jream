<?php

namespace Api;

use \User;
use \Promotion;

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

    public function applyPromotionAction()
    {
        $code = $this->input->getPost('code');
        $productId = $this->input->getPost('productId');

        $promotion = new Promotion();
        $result = $promotion->check($code, $productId);
    }

    public function freeAction()
    {
        $product = \Product::findFirstById($productId);
        if (!$product || $product->price != 0) {
            $this->flash->error('Sorry this is an invalid or non-free course.');

            return $this->redirect(self::REDIRECT_SUCCESS . $product->id);
        }

        $this->_createPurchase($product, 'free', 'website');

        return $this->redirect(self::REDIRECT_SUCCESS . $product->id);
    }

    public function stripeAction($productId)
    {
        $product = \Product::findFirstById($productId);
        if (!$product) {
            return $this->output->response(0, 'No product was found with the Id: %s', $productId);
        }

        if ($product->hasPurchased() == true) {
            return $this->output->response(0, 'You have already purchased this');
        }

        $stripeToken = $this->request->getPost('stripeToken');
        $name = $this->request->getPost('name');
        $zip = $this->request->getPost('zip');

        if (!$name) {
            return $this->output->response(0, 'You must provide a name.');
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
            if ($promo->expires_at > $this->helper->getLocaleTimestamp()) {
                $this->flash->error('Sorry, this promotion expired on ' . $promo->expires_at);

                return $this->redirect(self::REDIRECT_FAILURE . $product->slug);
            }

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
            return $this->output->response(0, $e->getMessage());
        }

        // Check failure of non 200
        if ((int)$response->getLastResponse()->code != 200) {
            $msg = "Sorry, the Stripe Gateway did not return a successful response.";
            $this->di->get('sentry')->captureMessage($msg);
            return $this->output->response(0, $e->getMessage());
        }

        // Check failure of paid false
        if ((int)$response->paid == 0) {
            $this->di->get('sentry')->captureException($response);
            return $this->output->response(0, 'There was a problem processing your payment');
        }

        if ((int)$response->paid == 1) {
            $this->_createPurchase($product, 'Stripe', $response->getLastResponse()->json['id']);
            return $this->output->response(1, 'Success.');
        }

        return $this->output->response(0, 'Sorry, your Stripe API Payment was not returned as paid.');
    }

    public function paypalAction()
    {
        $product = \Product::findFirstById($productId);

        if (!$product) {
            return $this->output->response(0, 'No product was found with the Id:' . $productId);
        }

        if ($product->hasPurchased() == true) {
            return $this->output->response(0, 'You have already purchased this');
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

    // If I change this make sure i change in paypal if i need to
    public function doPaypalConfirmAction()
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

    private function createPurchase()
    {
        # code...
    }
}

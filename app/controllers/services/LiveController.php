<?php
namespace Services;
use \Phalcon\Tag,
    \Omnipay\Omnipay;

class LiveController extends \BaseController
{

    /**
     * @return void
     */
    public function onConstruct()
    {
        parent::initialize();

        // Stripe
        $this->stripe_gateway = Omnipay::create('Stripe');
        $this->stripe_gateway->setApiKey($this->api->stripe->secretKey);

        // Paypal Express
        $this->paypal_gateway = Omnipay::create('PayPal_Express');
        $this->paypal_gateway->setUsername($this->api->paypal->username);
        $this->paypal_gateway->setPassword($this->api->paypal->password);
        $this->paypal_gateway->setSignature($this->api->paypal->signature);

        if ($this->api->paypal->testMode) {
            $this->paypal_gateway->setTestMode(true);
        }
    }

    // --------------------------------------------------------------

    /**
     * @return void
     */
    public function indexAction()
    {
        Tag::setTitle('Live Training | ' . $this->di['config']['title']);

        $this->view->setVars([
        ]);

        $this->view->pick('services/live');
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
        // $zip = $this->request->getPost('zip');

        if (!$name) {
            $this->flash->error('You must provide a name.');
            return $this->redirect(self::REDIRECT_FAILURE . $product->slug);
        }

        $amount = number_format($product->price, 2);

        // If a coupon is applied
        if ($this->session->has('code')) {
            $code = $this->session->get('code');
            $amount = number_format($code['price'], 2);
        }

        $response = $this->stripe_gateway->purchase([
            'amount'      => $amount,
            'currency'    => 'usd',
            'name'        => $name,
            'description' => $product->title,
            'metadata'    => [
                'name'    => $name,
                'user_id' => $this->session->get('id')
            ],
            'token' => $stripeToken,
        ])->send();

        if ($response->isSuccessful() == false) {
            $this->flash->error('There was a problem processing your payment');
            return $this->redirect(self::REDIRECT_FAILURE . $product->slug);
        }

        $data = $response->getData();

        if ($data['paid'])
        {
            $this->_createPurchase($product, 'Stripe', $response->getTransactionReference());
            return $this->redirect(self::REDIRECT_SUCCESS . $product->id);
        }

        $this->flash->error('Your stripe payment was not returned as paid');
        return $this->redirect(self::REDIRECT_FAILURE . $product->slug);
    }

}
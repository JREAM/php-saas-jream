<?php
use Stripe\Stripe;
use Omnipay\Omnipay;

/**
* ==============================================================
* API: Stripe
* =============================================================
*/
Stripe::setApiKey(getenv('STRIPE_SECRET'));

/**
* ==============================================================
* API: Paypal
* =============================================================
*/
$di->setShared('paypal', function () {
    // Paypal Express
    // @source  https://omnipay.thephpleague.com/gateways/configuring/
    $paypal = Omnipay::create('PayPal_Express');
    $paypal->setUsername(getenv('PAYPAL_USERNAME'));
    $paypal->setPassword(getenv('PAYPAL_PASSWORD'));
    $paypal->setSignature(getenv('PAYPAL_SIGNATURE'));

    if (getenv('PAYPAL_TESTMODE')) {
        $paypal->setTestMode(true);
    }

    return $paypal;
});

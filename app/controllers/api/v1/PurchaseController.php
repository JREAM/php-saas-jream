<?php
namespace Api;namespace Api\V1;

use \Phalcon\Tag;

class AuthController extends ApiBaseController
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

    }

    public function freeAction()
    {

    }

    public function stripeAction()
    {

    }

    public function paypalAction()
    {

    }

    // If I change this make sure i change in paypal if i need to
    public function doPaypalConfirmAction()
    {

    }

    // --------------------------------------------------------------

    private function createPurchase()
    {
        # code...
    }

}

// End of File
// --------------------------------------------------------------

<?php

namespace Test\Controllers;

/**
 * Class UnitTest
 */
class CheckoutControllerTest extends \UnitTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testModel()
    {
        $model = new \NewsletterSubscriptions();
        $this->assertTrue( is_object($model) );
    }

}

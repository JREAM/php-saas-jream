<?php

namespace Test\Models;

/**
 * Class UnitTest
 */
class NewsletterSubscriptionsTest extends \UnitTestCase
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

<?php

namespace Test\Models;

/**
 * Class UnitTest
 */
class PromotionTest extends \UnitTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testPromotion()
    {
        $promotion = new \Promotion();

        assertTrue( is_object($promotion) );
    }

}

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

    public function testModel()
    {
        $promotion = new \Promotion();
        $this->assertTrue( is_object($promotion) );
    }

}
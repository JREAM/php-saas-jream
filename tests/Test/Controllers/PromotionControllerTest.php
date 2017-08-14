<?php

namespace Test\Controllers;

/**
 * Class UnitTest
 */
class PromotionControllerTest extends \UnitTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testModel()
    {
        $model = new \ProductThread();
        $this->assertTrue( is_object($model) );
    }

}

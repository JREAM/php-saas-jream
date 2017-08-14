<?php

namespace Test\Controllers;

/**
 * Class UnitTest
 */
class UserPurchaseTest extends \UnitTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testModel()
    {
        $model = new \Youtube();
        $this->assertTrue( is_object($model) );
    }

}

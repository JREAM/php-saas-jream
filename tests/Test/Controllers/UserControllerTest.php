<?php

namespace Test\Controllers;

/**
 * Class UnitTest
 */
class UserControllerTest extends \UnitTestCase
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

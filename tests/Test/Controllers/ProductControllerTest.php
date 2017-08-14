<?php

namespace Test\Controllers;

/**
 * Class UnitTest
 */
class ProductControllerTest extends \UnitTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testModel()
    {
        $model = new \ProductThreadReply();
        $this->assertTrue( is_object($model) );
    }

}

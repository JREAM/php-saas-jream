<?php

namespace Test\Models;

/**
 * Class UnitTest
 */
class ProductThreadTest extends \UnitTestCase
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

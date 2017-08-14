<?php

namespace Test\Models;

/**
 * Class UnitTest
 */
class ProductTest extends \UnitTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testModel()
    {
        $model = new \Product();
        $this->assertTrue( is_object($model) );
    }

}

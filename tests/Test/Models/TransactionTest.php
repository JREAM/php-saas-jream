<?php

namespace Test\Models;

/**
 * Class UnitTest
 */
class TransactionTest extends \UnitTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testModel()
    {
        $model = new \Transaction();
        $this->assertTrue( is_object($model) );
    }

}

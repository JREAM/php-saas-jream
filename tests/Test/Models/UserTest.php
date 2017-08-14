<?php

namespace Test\Models;

/**
 * Class UnitTest
 */
class UserTest extends \UnitTestCase
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

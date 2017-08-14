<?php

namespace Test\Models;

/**
 * Class UnitTest
 */
class UserActionTest extends \UnitTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testModel()
    {
        $model = new \UserAction();
        $this->assertTrue( is_object($model) );
    }

}

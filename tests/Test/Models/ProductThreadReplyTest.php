<?php

namespace Test\Models;

/**
 * Class UnitTest
 */
class ProductThreadReplyTest extends \UnitTestCase
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

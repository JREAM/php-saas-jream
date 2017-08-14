<?php

namespace Test\Controllers;

/**
 * Class UnitTest
 */
class IndexControllerTest extends \UnitTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testModel()
    {
        $model = new \ProductCourse();
        $this->assertTrue( is_object($model) );
    }

}

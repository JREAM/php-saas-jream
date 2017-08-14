<?php

namespace Test\Models;

/**
 * Class UnitTest
 */
class ProductCourseTest extends \UnitTestCase
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

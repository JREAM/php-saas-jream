<?php

namespace Test\Models;

/**
 * Class UnitTest
 */
class ProductCourseMetaTest extends \UnitTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testModel()
    {
        $model = new \ProductCourseMeta();
        $this->assertTrue( is_object($model) );
    }

}

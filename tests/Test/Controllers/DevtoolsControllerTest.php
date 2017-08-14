<?php

namespace Test\Controllers;

/**
 * Class UnitTest
 */
class DevtoolsControllerTest extends \UnitTestCase
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

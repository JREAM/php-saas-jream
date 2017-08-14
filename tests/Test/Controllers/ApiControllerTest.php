<?php

namespace Test\Controllers;

/**
 * Class UnitTest
 */
class ApiControllerTest extends \UnitTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testModel()
    {
        $model = new \NewsletterResults();
        $this->assertTrue( is_object($model) );
    }

}

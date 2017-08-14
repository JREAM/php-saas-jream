<?php

namespace Test\Controllers;

/**
 * Class UnitTest
 */
class NewsletterControllerTest extends \UnitTestCase
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

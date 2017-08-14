<?php

namespace Test\Models;

/**
 * Class UnitTest
 */
class NewsletterTest extends \UnitTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testModel()
    {
        $model = new \Newsletter();
        $this->assertTrue( is_object($model) );
    }

}

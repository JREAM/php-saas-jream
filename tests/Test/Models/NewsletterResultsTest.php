<?php

namespace Test\Models;

/**
 * Class UnitTest
 */
class NewsletterResultsTest extends \UnitTestCase
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

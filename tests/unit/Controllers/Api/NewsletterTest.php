<?php

namespace App\Test\Unit\Controllers\Api;

use UnitTester;
use Codeception\Test\Unit;
use \User;

class NewsletterTest extends Unit
{
    /**
     * UnitTester Object
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
//        $this->user = new User;
    }

    protected function _after()
    {
    }

    public function testGetSource()
    {
//        $this->assertEquals($this->user->getSource(), 'user');
    }
}

<?php

namespace App\Test\Unit\Models;

use UnitTester;
use Codeception\Test\Unit;
use \User;

class PromotionTest extends Unit
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

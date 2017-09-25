<?php

namespace App\Test\Unit\Models;

use UnitTester;
use Codeception\Test\Unit;

class UserTest extends Unit
{
    /**
     * The Users model.
     * @var User
     */
    protected $user;

    /**
     * UnitTester Object
     * @var \UnitTester
     */
    protected $tester;

    // -----------------------------------------------------------------------------

    protected function _before()
    {
        $this->model = new \User;
    }

    // -----------------------------------------------------------------------------

    protected function _after()
    {
    }

    // -----------------------------------------------------------------------------

    public function testGetSource()
    {
        $this->assertEquals($this->user->getSource(), 'user');
    }

}

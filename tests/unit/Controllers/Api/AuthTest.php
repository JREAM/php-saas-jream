<?php

namespace App\Test\Unit\Controllers\Api;

use UnitTester;
use Codeception\Test\Unit;
use \User;

class AuthTest extends Unit
{
    /**
     * UnitTester Object
     *
     * @var \UnitTester
     */
    protected $tester;

    // -----------------------------------------------------------------------------

    protected function _before()
    {
        $this->controller = new \Controllers\Api\AuthController();
    }

    // -----------------------------------------------------------------------------

    protected function _after()
    {
    }

    // -----------------------------------------------------------------------------

    public function testLoginAction()
    {
//        $t = $this->controller->loginAction();
    }

    // -----------------------------------------------------------------------------

    public function testRegisterAction()
    {
//        $t = $this->controller->registerAction();
    }

    // -----------------------------------------------------------------------------

    public function testLogoutAction()
    {
//        $t = $this->controller->logoutAction();
    }

    // -----------------------------------------------------------------------------

    public function testPasswordResetAction()
    {
//        $t = $this->controller->passwordResetAction();
    }

    // -----------------------------------------------------------------------------

    public function testPasswordResetConfirmAction()
    {
//        $t = $this->controller->passwordResetConfirmAction();
    }

}

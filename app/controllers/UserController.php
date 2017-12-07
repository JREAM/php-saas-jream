<?php

namespace Controllers;

use Phalcon\Tag;
use Phalcon\Mvc\View;
use Phalcon\Http\Response;

class UserController extends BaseController
{

    /**
     * @return void
     */
    public function onConstruct(): void
    {
        parent::initialize();
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * Redirect a user to the Login or Dashboard
     *
     * @return Response
     */
    public function indexAction(): Response
    {
        if ($this->session->has('id')) {
            return $this->redirect('dashboard');
        }

        return $this->redirect('user/login');
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * Displays Login
     *
     * @return View
     */
    public function loginAction(): View
    {
        if ($this->session->has('id')) {
            $this->redirectToDashboard();
        }

        Tag::setTitle('Login | ' . $this->di[ 'config' ][ 'title' ]);

        $this->view->setVars([
            'form' => new \Forms\LoginForm(),
        ]);

        return $this->view->pick('user/login');
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * @return \Phalcon\Http\Response
     */
    protected function redirectToDashboard(): Response
    {
        return $this->redirect('dashboard');
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * Displays Register
     *
     * @return View
     */
    public function registerAction(): View
    {
        if ($this->session->has('id')) {
            $this->response->redirect('dashboard');

            return false;
        }

        Tag::setTitle('Register | ' . $this->di[ 'config' ][ 'title' ]);

        return $this->view->setVars([
            'form' => new \Forms\RegisterForm(),
        ]);
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * Displays Reset Password
     *
     * @return View
     */
    public function passwordAction(): View
    {
        Tag::setTitle('Forgot Password | ' . $this->di[ 'config' ][ 'title' ]);

        return $this->view->setVars([
            'form' => new \Forms\ForgotPasswordForm(),
        ]);
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * Displays Password Create
     *
     * @param  string $resetKey Generated key to confirm the user is asking for a reset
     *
     * @return View
     */
    public function passwordCreateAction(string $resetKey): ?View
    {
        $user = \User::findFirst([
            "password_reset_key = :key: AND password_reset_expires_at > :date:",
            "bind" => [
                "key"  => $resetKey,
                "date" => getDateTime(),
            ],
        ]);

        if (!$user) {
            $this->flash->error('Invalid key, or time has expired.');

            return $this->redirect('user/login');
        }

        Tag::setTitle('Create New Password | ' . $this->di[ 'config' ][ 'title' ]);
        $this->view->setVars([
            'reset_key' => $resetKey,
        ]);

        return $this->view->pick('user/password-create');
    }
}

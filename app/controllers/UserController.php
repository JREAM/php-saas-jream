<?php

namespace Controllers;

use \Phalcon\Tag;

class UserController extends BaseController
{
    const PASSWORD_REDIRECT_SUCCESS = 'user/login';

    /**
     * @return void
     */
    public function onConstruct()
    {
        parent::initialize();
//        $this->google_auth = $this->di->get('google_auth');
    }

    /**
     * Displays Login
     *
     * @return void
     */
    public function loginAction()
    {
        if ($this->session->has('id')) {
            return $this->redirect('dashboard');
        }

        Tag::setTitle('Login | ' . $this->di['config']['title']);

        $this->view->setVars([
            'form'       => new \Forms\LoginForm(),
            'fbLoginUrl' => $this->_getFacebookLoginUrl(),
//            'googleLogin' => $this->google_auth->createAuthUrl(),
        ]);

        $this->view->pick('user/login');
    }

    /**
     * Displays Register
     *
     * @return mixed
     */
    public function registerAction()
    {
        if ($this->session->has('id')) {
            $this->response->redirect('dashboard');

            return false;
        }

        Tag::setTitle('Register | ' . $this->di['config']['title']);

        // ---------------------------
        // Facebook Login
        // ---------------------------
        $fbLoginUrl = $this->_getFacebookLoginUrl();

        // ---------------------------
        // End Facebook
        // ---------------------------

        $this->view->setVars([
            'form'       => new \Forms\RegisterForm(),
            'fbLoginUrl' => $fbLoginUrl,
        ]);
    }

    /**
     * Displays Reset Password
     *
     * @return void
     */
    public function passwordAction()
    {
        Tag::setTitle('Forgot Password | ' . $this->di['config']['title']);
        $this->view->setVars([
            'form' => new \Forms\ForgotPasswordForm(),
        ]);
    }

    /**
     * Displays Password Create
     *
     * @param  $resetKey  Generated key to confirm the user is asking for a reset
     *
     * @return mixed
     */
    public function passwordCreateAction($resetKey)
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

            return $this->redirect(self::PASSWORD_REDIRECT_SUCCESS);
        }

        Tag::setTitle('Create New Password | ' . $this->di['config']['title']);
        $this->view->setVars([
            'reset_key' => $resetKey,
        ]);

        $this->view->pick('user/password-create');
    }

    /**
     * Retrieves Facebook Login URL
     *
     * @return string
     */
    private function _getFacebookLoginUrl()
    {
        // ---------------------------
        // Facebook Login
        // ---------------------------
        $helper = $this->facebook->getRedirectLoginHelper();

        return $helper->getLoginUrl(
            $this->api->fb->redirectUri,
            (array)$this->api->fb->scope
        );
    }

}

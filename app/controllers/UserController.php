<?php

namespace Controllers;

use Phalcon\Tag;
use Phalcon\Mvc\View;
use Phalcon\Http\Response;

class UserController extends BaseController
{
    const PASSWORD_REDIRECT_SUCCESS = 'user/login';

    // -----------------------------------------------------------------------------

    /**
     * @return void
     */
    public function onConstruct() : void
    {
        parent::initialize();
//        $this->google_auth = $this->di->get('google_auth');
    }

    // -----------------------------------------------------------------------------

    /**
     * Redirect a user to the Login or Dashboard
     *
     * @return Response
     */
    public function indexAction() : Response
    {
        if ($this->session->has('id')) {
            return $this->redirect('dashboard');
        }
        return $this->redirect('user/login');
    }

    // -----------------------------------------------------------------------------

    /**
     * Displays Login
     *
     * @return View
     */
    public function loginAction() : View
    {
        if ($this->session->has('id')) {
            $this->redirectToDashboard();
        }

        Tag::setTitle('Login | ' . $this->di['config']['title']);

        $this->view->setVars([
            'form'       => new \Forms\LoginForm(),
            'fbLoginUrl' => $this->_getFacebookLoginUrl(),
//            'googleLogin' => $this->google_auth->createAuthUrl(),
        ]);

        return $this->view->pick('user/login');
    }

    // -----------------------------------------------------------------------------

    /**
     * @return \Phalcon\Http\Response
     */
    protected function redirectToDashboard() : Response
    {
        return $this->redirect('dashboard');
    }

    // -----------------------------------------------------------------------------

    /**
     * Displays Register
     *
     * @return View
     */
    public function registerAction() : View
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

        return $this->view->setVars([
            'form'       => new \Forms\RegisterForm(),
            'fbLoginUrl' => $fbLoginUrl,
        ]);
    }

    // -----------------------------------------------------------------------------

    /**
     * Displays Reset Password
     *
     * @return View
     */
    public function passwordAction() : View
    {
        Tag::setTitle('Forgot Password | ' . $this->di['config']['title']);

        return $this->view->setVars([
            'form' => new \Forms\ForgotPasswordForm(),
        ]);
    }

    // -----------------------------------------------------------------------------

    /**
     * Displays Password Create
     *
     * @param  string $resetKey  Generated key to confirm the user is asking for a reset
     *
     * @return View
     */
    public function passwordCreateAction(string $resetKey) : ?View
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

        return $this->view->pick('user/password-create');
    }

    // -----------------------------------------------------------------------------


    /**
     * Retrieves Facebook Login URL
     *
     * @return string
     */
    private function _getFacebookLoginUrl() : string
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

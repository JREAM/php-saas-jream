<?php
use \Phalcon\Tag;

class UserController extends \BaseController
{
    const LOGIN_REDIRECT_SUCCESS = 'dashboard';
    const LOGIN_REDIRECT_FAILURE = 'user/login';

    const AUTH_REDIRECT_GOOGLE = 'user/dogooglelogin';

    const REGISTER_REDIRECT_SUCCESS = 'dashboard';
    const REGISTER_REDIRECT_FAILURE = 'user/register';

    const PASSWORD_REDIRECT_SUCCESS = 'user/login';
    const PASSWORD_REDIRECT_FAILURE = 'user/password';
    const PASSWORD_REDIRECT_FAILURE_PASSWD = 'user/passwordcreate/';

    // --------------------------------------------------------------

    /**
     * @return void
     */
    public function onConstruct()
    {
        parent::initialize();
    }

    // --------------------------------------------------------------

    /**
     * Displays Login
     *
     * @return void
     */
    public function loginAction()
    {
        if ($this->session->has('id')) {

            $this->redirect(self::LOGIN_REDIRECT_SUCCESS);
        }

        Tag::setTitle('Login');

        $this->view->setVars([
            'form'           => new \LoginForm(),
            'fbLoginUrl'     => $this->_getFacebookLoginUrl(),
            'tokenKey'       => $this->security->getTokenKey(),
            'token'          => $this->security->getToken()
        ]);

        $this->view->pick('user/login');
    }

    // --------------------------------------------------------------

    /**
     * Handles Login
     *
     * @return void
     */
    public function doLoginAction()
    {
        $this->view->disable();
        $result = $this->component->helper->csrf(self::LOGIN_REDIRECT_FAILURE);

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        // $remember_me = $this->request->getPost('remember_me');

        if (!$email || !$password) {
            $this->flash->error('email and password field(s) are required.');
            return $this->redirect(self::LOGIN_REDIRECT_FAILURE);
        }

        $user = User::findFirstByEmail($email);
        if ($user)
        {
            if ($user->is_deleted == 1) {
                $this->flash->error('This user has been permanently removed.');
                return $this->redirect(self::LOGIN_REDIRECT_FAILURE);
            }
            // Prevent Spam logins
            if ($user->login_attempt >= 5) {
                if (strtotime('now') < strtotime($user->login_attempt_at) + 600) {
                    $this->flash->error('Too many login attempts. Timed out for 10 minutes.');
                    return $this->redirect(self::LOGIN_REDIRECT_FAILURE);
                }
                // Clear the login attempts if time has expired
                $user->login_attempt = NULL;
                $user->login_attempt_at = NULL;
                $user->save();
            }

            if ($this->security->checkHash($password, $user->password))
            {
                if ($user->isBanned()) {
                    $this->flash->error('Sorry, your account has been locked due to suspicious activity.
                                For support, contact <strong>hello@jream.com</strong>.');
                    return $this->redirect(self::LOGIN_REDIRECT_FAILURE);
                }

                // $this->createSession($user, [], $remember_me);
                $this->createSession($user);
                return $this->redirect(self::LOGIN_REDIRECT_SUCCESS);
            }

            // Track the login attempts
            $user->login_attempt = $user->login_attempt + 1;
            $user->login_attempt_at = date('Y-m-d H:i:s', strtotime('now'));
            $user->save();
        }

        $this->flash->error('Incorrect Credentials');
        return $this->redirect(self::LOGIN_REDIRECT_FAILURE);
    }

    // --------------------------------------------------------------

    /**
     * Displays Facebook Login
     *
     * @return void
     */
    public function doFacebookLoginAction()
    {
        $helper = $this->facebook->getRedirectLoginHelper();

        try {
            $accessToken = $helper->getAccessToken();
        } catch(\Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            $this->di->get('sentry')->captureException($e);
            $this->flash->error('Facebook Graph returned an error: ' . $e->getMessage());
            return $this->redirect(self::LOGIN_REDIRECT_FAILURE);
        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            $this->di->get('sentry')->captureException($e);
            $this->flash->error('Facebook SDK returned an error: ' . $e->getMessage());
            return $this->redirect(self::LOGIN_REDIRECT_FAILURE);
        }

        // Missing Access Token
        if ( ! isset($accessToken))
        {
            if ($helper->getError()) {
                $this->di->get('sentry')->captureException($helper->getError());
                header('HTTP/1.0 401 Unauthorized');
                echo "Error: " . $helper->getError() . "\n";
                echo "Error Code: " . $helper->getErrorCode() . "\n";
                echo "Error Reason: " . $helper->getErrorReason() . "\n";
                echo "Error Description: " . $helper->getErrorDescription() . "\n";
            } else {
                header('HTTP/1.0 400 Bad Request');
                echo 'Bad request';
            }
            exit;
        }

        // Logged in
        try {
            // Returns a `Facebook\FacebookResponse` object (username is deprecated)
            $response = $this->facebook->get('/me?fields=id,name,email', $accessToken);
        } catch(\Facebook\Exceptions\FacebookResponseException $e) {
            error_log('Facebook Graph returned an error: ' . $helper->getMessage(), 0);
            $this->flash->error('Facebook Graph returned an error: ' . $e->getMessage());
            return $this->redirect(self::LOGIN_REDIRECT_FAILURE);
        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
            error_log('Facebook SDK returned an error: ' . $helper->getMessage(), 0);
            $this->flash->error('Facebook SDK returned an error: ' . $e->getMessage());
            return $this->redirect(self::LOGIN_REDIRECT_FAILURE);
        }

        // User is logged in with a long-lived access token.
        // You can redirect them to a members-only page.
        $facebookUser  = $response->getGraphUser();
        $facebookId    = $facebookUser->getId();
        $facebookEmail = $facebookUser->getEmail();

        // Generate the users name since it doesnt let you use a username anymore.
        $full_name = explode(' ', $facebookUser->getName());
        $first_name = $full_name[0];
        $last_initial = false;
        if (count($full_name) > 1) {
            $last_initial = end($full_name)[0];
        }
        $facebookName =  $first_name . ' ' . $last_initial;

        $user = \User::findFirstByFacebookId($facebookId);
        if ( ! $user) {
            $user = new \User();
            $user->role = 'user';
            $user->account_type = 'fb';
            $user->facebook_id = $facebookId;
            $user->facebook_email = $facebookEmail;
            $user->facebook_alias = $facebookName;
            $user->create();

            if ($user->getMessages()) {
                error_log('There was an error connecting your facebook user.', 0);
                $this->flash->error('There was an error connecting your facebook user.');
                return $this->redirect(self::LOGIN_REDIRECT_FAILURE);
            }

            // Where'd they signup from?
            $user->saveReferrer($user->id, $this->request);
        }
        else {
            // If the facebook users name or email changed
            if ($user->facebook_email != $facebookEmail) {
                $user->facebook_email = $facebookEmail;
            }
            if ($user->facebook_alias != $facebookName) {
                $user->facebook_alias = $facebookName;
            }
            $user->save();
        }

        if ($user->isBanned($user)) {
            $this->flash->error('Sorry, your account has been locked due to suspicious activity.
                                For support, contact <strong>hello@jream.com</strong>.');
            return $this->redirect(self::LOGIN_REDIRECT_FAILURE);
        }

        $this->createSession($user, [
            'fb_user_id'      => $facebookId,
            'fb_access_token' => $accessToken,
            'fb_logout_url'   => $helper->getLogoutUrl(
                $accessToken,
                \URL . '/' . self::LOGIN_REDIRECT_FAILURE
            )
        ]);

        return $this->redirect(self::LOGIN_REDIRECT_SUCCESS);
    }

    // --------------------------------------------------------------

    /**
     * Handles Confirm Email Change
     *
     * @param string $resetKey
     *
     * @return void
     */
    public function doConfirmEmailChangeAction($resetKey)
    {
        $user = User::findFirst([
            "email_change_key = :key: AND email_change_expires_at > :date:",
            "bind" => [
                "key" => $resetKey,
                "date" => getDateTime()
            ]
        ]);

        if (!$user) {
            $this->flash->error('Invalid key, or time has expired.');
            return $this->redirect(self::LOGIN_REDIRECT_FAILURE);
        }

        $user->email = $user->email_change;
        $user->email_change = null;
        $user->email_change_key = null;
        $user->email_change_expires_at = null;
        $user->save();

        if ($user->getMessages() == false)
        {
            $this->flash->success('Your email has been changed.');
        } else {
            $this->flash->error($user->getMessagesList());
        }

        //$this->_subscribeMailingList($email, 'update');

        return $this->redirect(self::LOGIN_REDIRECT_SUCCESS);
    }

    // --------------------------------------------------------------

    /**
     * Displays Register
     *
     * @return void
     */
    public function registerAction()
    {
        if ($this->session->has('id')) {
            return $this->response->redirect('dashboard');
        }

        Tag::setTitle('Register');

        // ---------------------------
        // Facebook Login
        // ---------------------------
        $fbLoginUrl = $this->_getFacebookLoginUrl();


        // ---------------------------
        // End Facebook
        // ---------------------------

        $this->view->setVars([
            'form' => new \RegisterForm(),
            'fbLoginUrl' => $fbLoginUrl,
            'tokenKey' => $this->security->getTokenKey(),
            'token' => $this->security->getToken()
        ]);
    }

    // --------------------------------------------------------------

    /**
     * Handles Register
     *
     * @return void
     */
    public function doRegisterAction()
    {
        $this->view->disable();

        // If AJAX Request
        if ($this->request->isAjax()) {
            $this->_doRegisterAjax();
            return;
        }

        $this->component->helper->csrf(self::REGISTER_REDIRECT_FAILURE);

        $alias = $this->request->getPost('alias');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $confirm_password = $this->request->getPost('confirm_password');

        if ($password != $confirm_password) {
            $this->flash->error('Your passwords do not match.');
            return $this->redirect(self::REGISTER_REDIRECT_FAILURE);
        }

        if (strlen($alias) < 4 || !ctype_alpha($alias)) {
            $this->flash->error('Alias must be atleast 4 characters and only alphabetical.');
            return $this->redirect(self::REGISTER_REDIRECT_FAILURE);
        }

        if (strlen($password) < 4 || strlen($password) > 128) {
            $this->flash->error('Your password must be 4-128 characters.');
            return $this->redirect(self::REGISTER_REDIRECT_FAILURE);
        }

        if (\User::findFirstByAlias($alias)) {
            $this->flash->error('Your alias cannot be used.');
            return $this->redirect(self::REGISTER_REDIRECT_FAILURE);
        }

        if (\User::findFirstByEmail($email)) {
            $this->flash->error('This email is already in use.');
            return $this->redirect(self::REGISTER_REDIRECT_FAILURE);
        }

        if (!Swift_Validate::email($email)) {
            $this->flash->error('Your email is invalid.');
            return $this->redirect(self::REGISTER_REDIRECT_FAILURE);
        }

        $user = new \User();
        $user->role = 'user';
        $user->account_type = 'default';
        $user->alias = $alias;
        $user->email = $email;
        $user->password = $this->security->hash($password);

        $result = $user->save();

        if (!$result)
        {
            $this->flash->error($user->getMessagesList());
            return $this->redirect(self::REGISTER_REDIRECT_FAILURE);
        }

        // Where'd they signup from?
        $user->saveReferrer($user->id, $this->request);

        $mail_result = $this->di->get('email', [
            [
            'to_name' => $user->getAlias($user->id),
            'to_email' => $user->getEmail($user->id),
            'from_name' => $this->config->email->from_name,
            'from_email' => $this->config->email->from_address,
            'subject' => 'JREAM Registration',
            'content' => $this->component->email->create('register', [])
            ]
        ]);

        if (! in_array($mail_result->_status_code, [200, 201, 202])) {
            $this->flash->error('You have successfully registered!
                                 However, there was a problem sending
                                 your welcome email.
                ');
        }
        else {
            $this->flash->success('You have successfully registered!');
        }

        $this->createSession($user);
        return $this->redirect(self::REGISTER_REDIRECT_SUCCESS);
    }

    // --------------------------------------------------------------

    /**
     * Handles Register as AJAX)
     *
     * @return void
     */
    private function _doRegisterAjax()
    {
        $error = [];
        if (!$this->component->helper->csrf()) {
            $error['csrf'] = 'Invalid CSRF';
        }

        $alias = $this->request->getPost('alias');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $confirm_password = $this->request->getPost('confirm_password');

        if ($password != $confirm_password) {
            $error['password'] = 'Your passwords do not match.';
        }

        if (strlen($alias) < 4 || !ctype_alpha($alias)) {
            $error['alias'] = 'Alias must be atleast 4 characters and only alphabetical.';
        }

        if (strlen($password) < 4 || strlen($password) > 128) {
            $error['password'] = 'Your password must be 4-128 characters.';
        }

        if (\User::findFirstByAlias($alias)) {
            $error['alias'] = 'Your alias cannot be used.';
        }

        if (\User::findFirstByEmail($email)) {
            $error['email'] = 'This email is already in use.';
        }

        if (!Swift_Validate::email($email)) {
            $error['email'] = 'Your email is invalid.';
        }


        if (!empty($error)) {
            $this->output(0, $error);
            return;
        }

        $user = new \User();
        $user->role = 'user';
        $user->alias = $alias;
        $user->email = $email;
        $user->password = $this->security->hash($password);

        $result = $user->save();

        if (!$result)
        {
            $error[] = $user->getMessagesList();
            $this->output(0, $error);
            return;
        }

        $mail_result = $this->di->get('email', [
            [
            'to_name' => $user->getAlias($user->id),
            'to_email' => $user->getEmail($user->id),
            'from_name' => $this->config->email->from_name,
            'from_email' => $this->config->email->from_address,
            'subject' => 'JREAM Registration',
            'content' => $this->component->email->create('register', [])
            ]
        ]);

        if (! in_array($mail_result->_status_code, [200, 201, 202])) {
            $message = 'You have successfully registered!
                             However, there was a problem sending
                             your welcome email.';
        } else {
            $message = 'You have successfully registered!';
        }

        //$this->_subscribeMailingList($email);

        $this->createSession($user);
        $this->output(1, [$message]);
    }

    // --------------------------------------------------------------

    // private function _subscribeMailingList($email, $updateExisting = false)
    // {
    //     try {
    //         $data = $this->mailchimp->lists->subscribe(
    //            $this->api->mailchimp->listId,
    //            ['email' => $email],
    //            null,
    //            'html',
    //            false, // Disable Double Opt in
    //            (bool) $updateExisting
    //        );
    //     } catch (\Exception $e) {
    //         // MailChimp Exceptions, maybe if a user is already in here.
    //         $this->sentry->captureException($e);
    //     }
    // }

    // --------------------------------------------------------------

    /**
     * Displays Reset Password
     *
     * @return void
     */
    public function passwordAction()
    {
        Tag::setTitle('Forgot Password');
        $this->view->setVars([
            'form'     => new \ForgotPasswordForm(),
            'tokenKey' => $this->security->getTokenKey(),
            'token'    => $this->security->getToken()
        ]);
    }

    // --------------------------------------------------------------

    /**
     * Handles Password Reset
     *
     * @return void
     */
    public function doPasswordResetAction()
    {
        $this->view->disable();
        $this->component->helper->csrf(self::PASSWORD_REDIRECT_FAILURE);

        $email = $this->request->getPost('email');
        $user = User::findFirstByEmail($email);

        if ($user)
        {
            $user->password_reset_key = hash('sha512', time() * rand(1, 9999));
            $user->password_reset_expires_at = date('Y-m-d H:i:s', strtotime('+10 minutes'));
            $user->update();

            if ($user->getMessages() == false)
            {
                $content = $this->component->email->create('confirm-password-change', [
                    'reset_link' => getBaseUrl('user/passwordcreate/' . $user->password_reset_key)
                ]);

                $mail_result = $this->di->get('email', [
                    [
                    'to_name' => $user->getAlias($user->id),
                    'to_email' => $user->getEmail($user->id),
                    'from_name' => $this->config->email->from_name,
                    'from_email' => $this->config->email->from_address,
                    'subject' => 'JREAM Password Reset',
                    'content' => $content
                    ]
                ]);

                if (! in_array($mail_result->_status_code, [200, 201, 202])) {
                    $this->flash->error('There was a problem sending the email.');
                }
                else {
                    $this->flash->success('A reset link has been sent to your email.
                                    You have 10 minutes to change your
                                    password before the link expires.
                    ');
                }

                return $this->redirect(self::PASSWORD_REDIRECT_SUCCESS);
            }

            $this->flash->error('An internal validation bug was encounterd.');
            return $this->redirect(self::PASSWORD_REDIRECT_FAILURE);
        }

        // Track the passwd attempts
        // $user->login_attempt = $user->login_attempt + 1;
        // $user->login_attempt_at = date('Y-m-d H:i:s', strtotime('now'));
        // $user->save();

        $this->flash->error('No email associated.');
        return $this->redirect(self::PASSWORD_REDIRECT_FAILURE);
    }

    // --------------------------------------------------------------

    /**
     * Displays Password Create
     *
     * @param  $resetKey  Generated key to confirm the user is asking for a reset
     *
     * @return void
     */
    public function passwordCreateAction($resetKey)
    {
        $user = \User::findFirst([
            "password_reset_key = :key: AND password_reset_expires_at > :date:",
            "bind" => [
                "key"  => $resetKey,
                "date" => getDateTime()
            ]
        ]);

        if (!$user) {
            $this->flash->error('Invalid key, or time has expired.');
            return $this->redirect(self::PASSWORD_REDIRECT_SUCCESS);
        }

        Tag::setTitle('Create New Password');
        $this->view->setVars([
            'reset_key' => $resetKey
        ]);
        $this->view->pick('user/password-create');
    }

    // --------------------------------------------------------------

    /**
     * Handles Password Create
     *
     * @return void
     */
    public function doPasswordCreateAction()
    {
        $this->view->disable();

        $confirmEmail = $this->request->getPost('email');
        $resetKey     = $this->request->getPost('reset_key');

        $this->component->helper->csrf(self::PASSWORD_REDIRECT_FAILURE_PASSWD . $resetKey);

        $user = User::findFirst([
            "email = :email: AND password_reset_key = :key: AND password_reset_expires_at > :date:",
            "bind" => [
                "email" => $confirmEmail,
                "key"   => $resetKey,
                "date"  => getDateTime()
            ]
        ]);

        if (!$user) {
            $this->flash->error('Invalid email and key combo, or time has expired.');
            return $this->redirect(self::PASSWORD_REDIRECT_FAILURE_PASSWD . "$resetKey");
        }

        $password = $this->request->getPost('password');
        $confirm_password = $this->request->getPost('confirm_password');

        if ($password != $confirm_password) {
            $this->flash->error('Your passwords do not match.');
            return $this->redirect(self::PASSWORD_REDIRECT_FAILURE_PASSWD . "$resetKey");
        }

        $user->password = $this->security->hash($password);
        $user->password_reset_key        = null;
        $user->password_reset_expires_at = null;
        $user->save();

        if ($user->getMessages() == false)
        {
            $this->flash->success('Your password has changed, please login.');
            return $this->redirect(self::PASSWORD_REDIRECT_SUCCESS);
        }

        $this->flash->error('There was an internal error updating.');
        return $this->redirect(self::PASSWORD_REDIRECT_FAILURE);
    }

    // --------------------------------------------------------------

    /**
     * Handles Logout
     *
     * @return void
     */
    public function logoutAction()
    {
        $this->destroySession();
        return $this->redirect('user/login');
    }

    // --------------------------------------------------------------

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
        return $helper->getLoginUrl($this->api->fb->redirectUri, (array) $this->api->fb->scope);
    }

    // --------------------------------------------------------------

}
// End of File
// --------------------------------------------------------------

<?php
namespace Api\V1;

use \Phalcon\Tag;

class AuthController extends ApiBaseController
{

    /**
     * @return void
     */
    public function onConstruct()
    {
        parent::initialize();
    }

    // --------------------------------------------------------------

    public function loginAction()
    {
        if (!$this->component->helper->csrf(false, true)) {
            return $this->output(0, 'Invalid CSRF');
        }

        // POST Data
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        if (!$email || !$password) {
            return $this->output(0, 'email and password field(s) are required.');
        }

        // Find the user based on the email
        $user = User::findFirstByEmail($email);
        if ($user)
        {
            if ($user->is_deleted == 1)
            {
                return $this->output(0, 'This user has been permanently removed.');
            }

            // Prevent Spam logins
            if ($user->login_attempt >= 5)
            {
                if (strtotime('now') < strtotime($user->login_attempt_at) + 600) {
                    return $this->output(0, 'Too many login attempts. Timed out for 10 minutes.');
                }

                // Clear the login attempts if time has expired
                $user->login_attempt = null;
                $user->login_attempt_at = null;
                $user->save();
            }

            if ($this->security->checkHash($password, $user->password))
            {
                if ($user->isBanned()) {
                    return $this->output(0, 'Sorry, your account has been locked due to suspicious activity.
                                For support, contact <strong>hello@jream.com</strong>.');

                    return $this->redirect(self::LOGIN_REDIRECT_FAILURE);
                }

                // $this->createSession($user, [], $remember_me);
                $this->createSession($user);
                return $this->output(1, 'Login Success.');
            }

            // Track the login attempts
            $user->login_attempt = $user->login_attempt + 1;
            $user->login_attempt_at = date('Y-m-d H:i:s', strtotime('now'));
            $user->save();
        }

        return $this->output(0, 'Incorrect Credentials');
    }

    public function registerAction()
    {

    }

    public function forgotPasswordAction()
    {

    }

    public function createNewPasswordAction()
    {

    }

}

// End of File
// --------------------------------------------------------------

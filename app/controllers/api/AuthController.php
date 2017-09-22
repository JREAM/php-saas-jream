<?php
declare(strict_types = 1);

namespace Controllers\Api;

use Phalcon\Http\Response;
use User;

class AuthController extends ApiController
{

    /**
     * @return void
     */
    public function onConstruct()
    {
        parent::initialize();
    }

    // -----------------------------------------------------------------------------

    /**
     * @return string JSON
     */
    public function loginAction() : Response
    {
        // POST Data
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Cannot have Empty Fields
        if( ! $email || ! $password) {
            return $this->output(0, 'email and password field(s) are required.');
        }

        // Find the user based on the email
        $user = User::findFirstByEmail($email);
        if( ! $user) {
            return $this->output(0, 'Incorrect Credentials');
        }

        if($user->is_deleted == 1) {
            return $this->output(0, 'This user has been permanently removed.');
        }

        // Prevent Spam logins
        if($user->login_attempt >= 5) {
            if(strtotime('now') < strtotime($user->login_attempt_at) + 600) {
                return $this->output(0, 'Too many login attempts. Timed out for 10 minutes.');
            }

            // Clear the login attempts if time has expired
            $user->login_attempt = null;
            $user->login_attempt_at = null;
            $user->save();
        }

        if($this->security->checkHash($password, $user->password)) {
            // Check Banned
            if($user->isBanned()) {
                return $this->output(0, '
                    Sorry, your account has been locked due to suspicious activity.
                    For support, contact <b>hello@jream.com</b>.
                    ');
            }

            // $this->createSession($user, [], $remember_me);
            $this->createSession($user);

            return $this->output(1, 'Logging In!', [
                'redirect' => getBaseUrl('dashboard'),
            ]);
        }

        // Track the login attempts
        ++$user->login_attempt;
        $user->login_attempt_at = date('Y-m-d H:i:s', strtotime('now'));
        $user->save();

        return $this->output(0, 'Incorrect Credentials');
    }

    // -----------------------------------------------------------------------------

    public function googleAction()
    {
        $client = $this->di->get('google');

        // Check if an auth token exists for the required scopes
        $tokenSessionKey = 'token-' . $client->prepareScopes();

        // If Code, Forward to Request Access Token
        if ($this->request->get('code'))
        {
            if ($this->session->get('state') != $this->request->get('state')) {
                throw new \RuntimeException('The session state did not match for Google.');
            }
            $client->authenticate($this->request->get('code'));
            $this->session->set($tokenSessionKey, $client->getAccessToken());
            // @TODO: WHERE IS THIS REDIRECT?
            $redirect = sprintf('%s%s', $this->di->get('config')->url, ltrim($this->router->getRewriteUri(), '/'));
            $this->response->redirect($redirect);
        }

        // If Access Token (from previous) is set, set in client
        if ($this->session->has($tokenSessionKey)) {
          $client->setAccessToken($this->session->get($tokenSessionKey));
        }

        // Check to ensure that the access token was successfully acquired.
        if ($client->getAccessToken()) {
            try {
                // @TODO Service Here!!!
                $plus = new Google_Service_Plus_Person($client);
                // @TODO Save to DB if not exists, otherwise login, refresh token
                return $this->output(1, 'Logged In', [
                    'redirect' => getBaseUrl('dashboard')
                ]);
            } catch (Google_Service_Exception $e) {
                return $this->output(0, $e->getMessage());
            } catch (Google_Exception $e) {
                return $this->output(0, $e->getMessage());
            }
        }

        $state = mt_rand();
        $client->setState($state);
        $this->session->set('state', $state);

        return $this->output(0, 'Not authenticated, login with the URL', [
            'url' => $client->createAuthUrl()
        ]);

    }

    // -----------------------------------------------------------------------------

    /**
     * Does the Login via Facebook Auth
     *
     * @return string   JSON
     */
    public function facebookAction() : Response
    {
        $helper = $this->facebook->getRedirectLoginHelper();

        try {
            $accessToken = $helper->getAccessToken();
        } catch
        (\Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            $this->di->get('sentry')->captureException($e);

            return $this->output(0,
                'Facebook Graph returned an error: ' . $e->getMessage()
            );
        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            $this->di->get('sentry')->captureException($e);

            return $this->output(0,
                'Facebook SDK returned an error: ' . $e->getMessage()
            );
        }

        // Missing Access Token
        if( ! isset($accessToken)) {
            if($helper->getError()) {
                $this->di->get('sentry')->captureException($helper->getError());
                header('HTTP/1.0 401 Unauthorized');
                echo "Error: " . $helper->getError() . "\n";
                echo "Error Code: " . $helper->getErrorCode() . "\n";
                echo "Error Reason: " . $helper->getErrorReason() . "\n";
                echo "Error Description: " . $helper->getErrorDescription() . "\n";
            }
            else {
                header('HTTP/1.0 400 Bad Request');
                echo 'Bad request';
            }
            exit;
        }

        // Logged in
        try {
            // Returns a `Facebook\FacebookResponse` object (username is deprecated)
            $response = $this->facebook->get(
                '/me?fields=id,name,email',
                $accessToken
            );
        } catch(\Facebook\Exceptions\FacebookResponseException $e) {
            error_log(
                'Facebook Graph returned an error: ' . $helper->getMessage(), 0
            );

            return $this->output(0,
                'Facebook Graph returned an error: ' . $e->getMessage()
            );
        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
            error_log('Facebook SDK returned an error: ' . $helper->getMessage(), 0);

            return $this->output(0,
                'Facebook SDK returned an error: ' . $e->getMessage()
            );
        }

        // User is logged in with a long-lived access token.
        // You can redirect them to a members-only page.
        $facebookEmail  = $facebookUser->getEmail();
        $facebookId     = $facebookUser->getId();
        $facebookUser   = $response->getGraphUser();

        // Generate the users name since it doesnt let you use a username anymore.
        $full_name = explode(' ', $facebookUser->getName());
        $first_name = $full_name[0];
        $last_initial = false;
        if(count($full_name) > 1) {
            $last_initial = end($full_name)[0];
        }
        $facebookName = $first_name . ' ' . $last_initial;

        $user = \User::findFirstByFacebookId($facebookId);
        if( ! $user) {
            $user = new \User();
            $user->role = 'user';
            $user->account_type = 'fb';
            $user->facebook_id = $facebookId;
            $user->facebook_email = $facebookEmail;
            $user->facebook_alias = $facebookName;
            $user->create();

            if($user->getMessages()) {
                error_log('There was an error connecting your facebook user.', 0);

                return $this->output(0,
                    'There was an error connecting your facebook user.'
                );
            }

            // Where'd they signup from?
            $user->saveReferrer($user->id, $this->request);
        }
        else {
            // If the facebook users name or email changed
            if($user->facebook_email != $facebookEmail) {
                $user->facebook_email = $facebookEmail;
            }
            if($user->facebook_alias != $facebookName) {
                $user->facebook_alias = $facebookName;
            }
            $user->save();
        }

        if($user->isBanned()) {
            return $this->output(0,
                'Sorry, your account has been locked due to suspicious activity.
                        For support, contact <b>hello@jream.com</b>.'
            );
        }

        $this->createSession($user, [
            'fb_user_id'      => $facebookId,
            'fb_access_token' => $accessToken,
            'fb_logout_url'   => $helper->getLogoutUrl(
                $accessToken,
                \URL . '/' . 'user/login'
            ),
        ]);

        return $this->output(1, ['redirect' => 'dashboard']);
    }

    // -----------------------------------------------------------------------------

    /**
     * @return string JSON
     */
    public function registerAction() : Response
    {
        $alias = $this->request->getPost('alias');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $confirm_password = $this->request->getPost('confirm_password');

        // GOTTA TEST THIS
        // @TODO this is NOT VALID but its not working
        $form = new \Forms\RegisterForm(null, ['confirm_password' => $confirm_password]);
        if (!$form->isValid($_POST)) {
            foreach ($form->getMessages() as $msg) {
                print_r($msg);
                echo $msg->getField();
                echo $msg->getCode();
                echo $msg->getType();
                echo $msg->getMessage();
            }
            die;
            return $this->output(0, null, $form->getMessagesArray());
        }

        if(\User::findFirstByAlias($alias)) {
            return $this->output(0,
                'Your alias cannot be used.'
            );
        }

        if(\User::findFirstByEmail($email)) {
            return $this->output(0,
                'This email is already in use.'
            );
        }

        if( ! Swift_Validate::email($email)) {
            return $this->output(0,
                'Your email is invalid.'
            );
        }

        $user = new \User();
        $user->role = 'user';
        $user->account_type = 'default';
        $user->alias = $alias;
        $user->email = $email;
        $user->password = $this->security->hash($password);
        // Create a unique hash per user
        $user->password_salt = $this->security->hash(random_int(5000, 100000));

        $result = $user->save();

        if( ! $result) {
            return $this->output(0, $user->getMessagesList());
        }

        // Save them in the mailing list
        $newsletterSubscription = new \NewsletterSubscription();
        $newsletterSubscription->email = $email;
        $newsletterSubscription->is_subscribed = 1; // @TODO is tihs right?
        $newsletterSubscription->save();

        // Where'd they signup from?
        $user->saveReferrer($user->id, $this->request);

        // Send an email!
        $mail_result = $this->di->get('email', [
            [
                'to_name'    => $user->getAlias($user->id),
                'to_email'   => $user->getEmail($user->id),
                'from_name'  => $this->config->email->from_name,
                'from_email' => $this->config->email->from_address,
                'subject'    => 'JREAM Registration',
                'content'    => $this->component->email->create('register', []),
            ],
        ]);

        // If email error, oh well still success
        $message = 'You have successfully registered!';
        if ( ! in_array($mail_result->statusCode(), [200, 201, 202])) {
            $message = 'You have successfully registered!
                                 However, there was a problem sending
                                 your welcome email.
                ';
        }

        // Create the User Session
        $this->createSession($user);

        return $this->output(1, $message);
    }

    // -----------------------------------------------------------------------------

    /**
     * @return string|Response
     */
    public function logoutAction()
    {
        if($this->session->has('facebook_id')) {
            $this->session->destroy();
            $this->facebook->destroySession();
            $this->facebook->setAccessToken('');

            return $this->output(1, [
                'redirect' => $this->response->redirect($this->facebook->getLogoutUrl(), true),
            ]);
        }

        $this->session->destroy();

        return $this->response->redirect($this->router->getRouteByName('home'));
    }

    // -----------------------------------------------------------------------------

    /**
     * @return string JSON
     */
    public function passwordForgotAction() : Response
    {
        $email = $this->request->getPost('email');
        $user = User::findFirstByEmail($email);

        if( ! $user) {
            return $this->output(0, 'No email associated.');
        }

        $user->password_reset_key = hash('sha512', time() * random_int(1, 9999));
        $user->password_reset_expires_at = date('Y-m-d H:i:s', strtotime('+10 minutes'));
        $user->update();

        if($user->getMessages()) {
            return $this->output(0, 'An internal update to the user occurred.');
        }

        // Email: Generate
        $content = $this->component->email->create('confirm-password-change', [
            'reset_link' => getBaseUrl('user/passwordcreate/' . $user->password_reset_key),
        ]);

        // Email: Send
        $mail_result = $this->di->get('email', [
            [
                'to_name'    => $user->getAlias($user->id),
                'to_email'   => $user->getEmail($user->id),
                'from_name'  => $this->config->email->from_name,
                'from_email' => $this->config->email->from_address,
                'subject'    => 'JREAM Password Reset',
                'content'    => $content,
            ],
        ]);

        // Email: If the status code is not 200 the mail didn't send.
        if( ! in_array($mail_result->statusCode(), [200, 201, 202])) {
            return $this->output(0, 'There was a problem sending the email.');
        }

        return $this->output(0, 'A reset link has been sent to your email.
            You have 10 minutes to change your
            password before the link expires.'
        );
    }

    // -----------------------------------------------------------------------------

    /**
     * @return string JSON
     */
    public function passwordForgotCreateAction() : Response
    {
        $confirmEmail = $this->request->getPost('email');
        $resetKey = $this->request->getPost('reset_key');

        $user = User::findFirst([
            "email = :email: AND password_reset_key = :key: AND password_reset_expires_at > :date:",
            "bind" => [
                "email" => $confirmEmail,
                "key"   => $resetKey,
                "date"  => getDateTime(),
            ],
        ]);

        if( ! $user) {
            return $this->output(0, 'Invalid email and key combo, or time has expired.');
        }

        $password = $this->request->getPost('password');
        $confirm_password = $this->request->getPost('confirm_password');

        if($password != $confirm_password) {
            return $this->output(0, 'Your passwords do not match.');
        }

        // Create the new password, set a new salt and reset key
        $user->password = $this->security->hash($password);
        $user->password_salt = $this->security->hash(random_int(5000, 100000));
        $user->password_reset_key = null;
        $user->password_reset_expires_at = null;
        $user->save();

        if($user->getMessages()) {
            return $this->output(0, 'There was an internal error updating.');
        }

        return $this->output(1, 'Your password has changed, please login.');
    }

    // -----------------------------------------------------------------------------

    /**
     * Creates a User Session
     *
     * @param \User $user       User Model
     * @param array $additional Additional values to add to session
     *
     * @return void
     */
    protected function createSession(\User $user, array $additional = []) : void
    {
        // Clear the login attempts
        $user->login_attempt = null;
        $user->login_attempt_at = null;

        $this->session->set('id', $user->id);
        $this->session->set('role', $user->role);
        $this->session->set('alias', $user->getAlias());

        $use_timezone = 'utc';
        if(property_exists($user, 'timezone')) {
            $use_timezone = $user->timezone;
        }

        $this->session->set('timezone', $use_timezone);

        if(is_array($additional)) {
            foreach($additional as $_key => $_value) {
                $this->session->set($_key, $_value);
            }
        }

        // Delete old session so multiple logins aren't allowed
        session_regenerate_id(true);

        $user->session_id = $this->session->getId();
        $user->save();

        // If the user changes web browsers, prevent a hijacking attempt
        $this->session->set('agent', $_SERVER['HTTP_USER_AGENT']);
    }

}

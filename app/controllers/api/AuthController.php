<?php

declare(strict_types=1);

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
    public function loginAction(): Response
    {
        // POST Data
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Cannot have Empty Fields
        if ( ! $email || ! $password) {
            return $this->output(0, 'email and password field(s) are required.');
        }

        // Find the user based on the email
        $user = User::findFirstByEmail($email);
        if ( ! $user) {
            return $this->output(0, 'Incorrect Credentials');
        }

        if ($user->is_deleted == 1) {
            return $this->output(0, 'This user has been permanently removed.');
        }

        // Prevent Spam logins
        if ($user->login_attempt >= 5) {
            if (strtotime('now') < strtotime($user->login_attempt_at) + 600) {
                return $this->output(0, 'Too many login attempts. Timed out for 10 minutes.');
            }

            // Clear the login attempts if time has expired
            $user->login_attempt    = null;
            $user->login_attempt_at = null;
            $user->save();
        }

        if ($this->security->checkHash($password, $user->password)) {
            // Check Banned
            if ($user->isBanned()) {
                return $this->output(0, '
                    Sorry, your account has been locked due to suspicious activity.
                    For support, contact <b>hello@jream.com</b>.
                    ');
            }

            $this->createSession($user, 'jream');

            // @TODO Do a real redirect instead ?
            return $this->output(1, 'Logging In!', [
                'redirect' => \Library\Url::get('dashboard'),
            ]);
        }

        // Track the login attempts
        // @TODO should i save in session?
        ++$user->login_attempt;
        $user->login_attempt_at = date('Y-m-d H:i:s', strtotime('now'));
        $user->save();

        return $this->output(0, 'Incorrect Credentials');
    }

    // -----------------------------------------------------------------------------

    /**
     * Github Login
     */
    public function githubAction()
    {
        try {
            $adapter = $this->hybridauth->authenticate('GitHub');

            if ($adapter->isConnected()) {
                $profile = $adapter->getUserProfile();

                // debug here:
                pr($profile);
                // @TODO: save user image?
                // @todo: save temporary alias?

                $profile->identifier;
                $profile->profileURL;
                // $avatar = https://avatars0.githubusercontent.com/u/{$id}?v=4

                // Prefer the verified email if its set
                $email = $profile->emailVerified ?: $profile->email;

                //$this->saveUser('github',
                //    $profile->identifier
                //    //$profile->ALIAS, // Need to figure this out
                //    $email,
                //);
                $this->createSession($user, 'github');

                return $response->redirect('dashboard/');
            }

            // @TODO When to use disconnect?

            //$boolean = $this->hybridauth->isConnectedWith('Github');
            //$boolean = $this->hybridauth->isConnectedWith('Google');
            //$boolean = $this->hybridauth->isConnectedWith('Facebook');
            //$adapter = $this->hybridauth->getAdapter('Github');
            //$adapter = $this->hybridauth->getAdapter('Google');
            //$adapter = $this->hybridauth->getAdapter('Facebook');

            $adapter->disconnect();
        } catch (\Exception $e) {
            $this->view->setVars([
                'message' => $e->getMessage(),
            ]);

            return $this->view->pick('error/generic');
        }

        die;
    }

    // -----------------------------------------------------------------------------

    public function googleAction()
    {
        try {
            $adapter = $this->hybridauth->authenticate('Google');

            if ($adapter->isConnected()) {
                $profile = $adapter->getUserProfile();

                $profile->identifier;
                $profile->profileURL;
                // $avatar = https://avatars0.githubusercontent.com/u/{$id}?v=4

                pr($profile);

                // Prefer the verified email if its set
                $email = ($profile->emailVerified) ?: $profile->email;

                //$this->saveUser('github',
                //    $profile->identifier
                //    //$profile->ALIAS, // Need to figure this out
                //    $email,
                //);
                $this->createSession($user, 'google');

                return $response->redirect('dashboard/');
            }

            var_dump($userProfile);

            // @TODO When to use disconnect?
            $adapter->disconnect();
        } catch (\Exception $e) {
            $this->view->setVars([
                'message' => $e->getMessage(),
            ]);

            return $this->view->pick('error/generic');
        }
    }

    // -----------------------------------------------------------------------------

    /**
     * Does the Login via Facebook Auth
     *
     * @return Response|View
     */
    public function facebookAction()
    {
        try {
            $adapter = $this->hybridauth->authenticate('Facebook');

            if ($adapter->isConnected()) {
                $profile = $adapter->getUserProfile();

                pr($profile);

                $profile->identifier;
                $profile->profileURL;
                // $avatar = https://avatars0.githubusercontent.com/u/{$id}?v=4

                // Prefer the verified email if its set
                $email = ($profile->emailVerified) ?: $profile->email;

                //$this->saveUser('github',
                //    $profile->identifier
                //    //$profile->ALIAS, // Need to figure this out
                //    $email,
                //);

                $this->createSession($user, 'facebook');

                return $response->redirect('dashboard/');
            }

            var_dump($userProfile);

            // @TODO When to use disconnect?
            $adapter->disconnect();
        } catch (\Exception $e) {
            $this->view->setVars([
                'message' => $e->getMessage(),
            ]);

            return $this->view->pick('error/generic');
        }
    }

    // -----------------------------------------------------------------------------

    /**
     * @return string JSON
     */
    public function registerAction(): Response
    {
        // @TODO use the saveUser method
        $alias            = $this->request->getPost('alias');
        $email            = $this->request->getPost('email');
        $password         = $this->request->getPost('password');
        $confirm_password = $this->request->getPost('confirm_password');

        // GOTTA TEST THIS
        // @TODO this is NOT VALID but its not working
        $form = new \Forms\RegisterForm(null, ['confirm_password' => $confirm_password]);
        if ( ! $form->isValid($_POST)) {
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

        if (\User::findFirstByAlias($alias)) {
            return $this->output(0, 'Your alias cannot be used.');
        }

        if (\User::findFirstByEmail($email)) {
            return $this->output(0, 'This email is already in use.');
        }

        if ( ! Swift_Validate::email($email)) {
            return $this->output(0, 'Your email is invalid.');
        }

        $user               = new \User();
        $user->role         = 'user';
        $user->account_type = 'default';
        $user->alias        = $alias;
        $user->email        = $email;
        $user->password     = $this->security->hash($password);
        // Create a unique hash per user
        $user->password_salt = $this->security->hash(random_int(5000, 100000));

        $result = $user->save();

        if ( ! $result) {
            return $this->output(0, $user->getMessagesAsHTML());
        }

        // Save them in the mailing list
        $newsletterSubscription                = new \NewsletterSubscription();
        $newsletterSubscription->email         = $email;
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
        if ( ! in_array($mail_result->statusCode(), [200, 201, 202])) {
            $message = '
                You have successfully registered!
                However, there was a problem sending
                your welcome email.
            ';
            $this->flashSession->warning($message);
        } else {
            $message = 'You have successfully registered!';
            $this->flashSession->success($message);
        }

        // Create the User Session
        $this->createSession($user, 'jream');

        return $response->redirect('dashboard/');
    }

    // -----------------------------------------------------------------------------

    /**
     * Saves a user to the Database for Local or Social Network
     *
     * @param string      $type            Can be: github, google, facebook, jream
     * @param int         $id
     * @param string      $alias
     * @param string      $email
     * @param string|null $hashed_password For local jream accounts
     *
     * @throws \InvalidArgumentException
     *
     * @return mixed
     */
    protected function saveUser(string $type, int $id, string $alias, string $email, $hashed_password = null)
    {
        $type            = strtolower($type);
        $isNewUser       = false; // This can change below if they exist on another platform
        $isLinkedAccount = false; // This changes if an existing account has their email saved
        $accountTypes    = [
            'local'  => [
                'jream',
            ],
            'social' => [
                'github',
                'google',
                'facebook',
            ],
        ];

        // Flat Array
        $accepted = array_merge($accountTypes [ 'local' ], $accountTypes [ 'social' ]);

        if ( ! in_array($type, $accepted)) {
            throw new \InvalidArgumentException(sprintf('%s: %s', 'The user type is not valid, must be one of', implode(', ', $accepted)));
        }

        // If they have an email already, associate this with their account.
        $searchUser = User::findFirst([
            '
            email = :email 
            OR github_email = :email 
            OR google_email = :email 
            OR facebook_email = :email 
            ',
        ], [
            'bind' => [
                'email' => $email,
            ],
        ]);

        // They cannot get to this point with the same account they are registering.
        // This is to update an existing record
        if ($searchUser) {
            // Existing: Local Account (jream)
            if ($searchUser->email) {
                $user = \User::findFirstByEmail($searchUser->email);
            }
            // Social Accounts
            // Below get an existing User if they exist for updating.
            // We only need one record.

            // Existing: Social Account (github)
            else if ($searchUser->github_email) {
                $user = \User::findFirstByGithubEmail($searchUser->github_email);
            } // Existing: Social Account (google)
            else if ($searchUser->google_email) {
                $user = \User::findFirstByGoogleEmail($searchUser->google_email);
            } // Existing: Social Account (facebook)
            else if ($searchUser->facebook_email) {
                $user = \User::findFirstByFacebookEmail($searchUser->facebook_email);
            }

            // This account is linked with an existing, use one account.
            if ($user) {
                $isLinkedAccount = true;
            }
        }
        // If user does not exist in any form, create a new one.
        if ( ! $user) {
            $isNewUser = true;
            $user      = new \User();
        }

        $user->role         = 'user';
        $user->account_type = 'default';

        if (in_array($type, $accountTypes [ 'social' ], true)) {
            $field_id    = "{$type}_id";
            $field_alias = "{$type}_alias";
            $field_email = "{$type}_email";

            // user->github_id, facebook_id, etc..
            $user->$field_id    = $id;
            $user->$field_alias = $alias;
            $user->$field_email = $email;
        } else {
            // for jream accounts, id is automatically assigned
            $user->alias = $alias;
            $user->email = $email;

            // This is only for jream accounts
            $user->password      = $hashed_password;
            $user->password_salt = $this->security->hash(random_int(5000, 100000));
        }

        $result = $user->save();
        // Save where they signed up from, the Social Auth might not do this so great.
        $user->saveReferrer($user->id, $this->request);

        // @TODO this may need to return to the parent function rather than output
        if ( ! $result) {
            return $this->output(0, $user->getMessagesAsHTML());
        }

        // @important Create the User Session
        $this->createSession($user);

        // @TODO Ensure the user->id is saved, and accessible after creation
        if ($isNewUser) {
            // New Users are Saved to the mailing list
            $newsletterSubscription                = new \NewsletterSubscription();
            $newsletterSubscription->user_id       = $user->id; // the new ID received
            $newsletterSubscription->email         = $email;
            $newsletterSubscription->is_subscribed = 1; // @TODO is tihs right?
            $newsletterSubscription->save();

            //@TODO Ensure passing the created user instance works
            $this->sendWelcomeEmail($user, $type);
        }

        if ($isLinkedAccount) {
            $this->sendLinkedAccountEmail($user, $type);
        }


        return 1;
    }


    // -----------------------------------------------------------------------------

    /**
     * Delivers welcome email to first time signups
     *
     * @param \User  $user        The User
     * @param string $accountType The network: local/jream, facebook, github, google, etc.
     */
    protected function sendWelcomeEmail(\User $user, string $accountType)
    {
        $mail_result = $this->di->get('email', [
            [
                'to_name'    => $user->getAlias($user->id),
                'to_email'   => $user->getEmail($user->id),
                'from_name'  => $this->config->email->from_name,
                'from_email' => $this->config->email->from_address,
                'subject'    => 'JREAM Registration',
                'content'    => $this->component->email->create('register', [
                    'type' => $accountType,
                ]),
            ],
        ]);

        // If email error, oh well still success
        $message = 'You have successfully registered with a new ' . ucwords($accountType) . ' account!';
        if ( ! in_array($mail_result->statusCode(), [200, 201, 202])) {
            // @TODO remove them from the newsletter list with THIS email, not the ALIAS,
            // but based on $accountType and $email (Because a diff one could be registered)
            $message .= "However, there was a problem sending your welcome email to: {$user->getEmail($user->id)}!";
        }

        return $message;
    }

    // -----------------------------------------------------------------------------

    /**
     * Delivers linked account email when logging in with social network to existing account.
     *
     * @param \User  $user        The User
     * @param string $accountType The network: local/jream, facebook, github, google, etc.
     *
     * @return string
     */
    protected function sendLinkedAccountEmail(\User $user, string $accountType): string
    {
        $mail_result = $this->di->get('email', [
            [
                'to_name'    => $user->getAlias($user->id),
                'to_email'   => $user->getEmail($user->id),
                'from_name'  => $this->config->email->from_name,
                'from_email' => $this->config->email->from_address,
                'subject'    => 'JREAM Linked Account',
                'content'    => $this->component->email->create('register_linked', [
                    'type' => ucwords($accountType),
                ]),
            ],
        ]);

        // If email error, oh well still success
        $message = 'You have Successfully Linked your {$accountType} account!';
        if ( ! in_array($mail_result->statusCode(), [200, 201, 202])) {
            $message = "However, there was a problem sending to you email: {$user->getEmail($user->id)}!";
            // @TODO remove them from the newsletter list with THIS email, not the ALIAS,
            // but based on $accountType and $email (Because a diff one could be registered)
        }

        return $message;
    }

    // -----------------------------------------------------------------------------

    /**
     * @return string|Response
     */
    public function logoutAction()
    {
        // @TODO This is broken
        //$this->hybridauth->disconnectAllAdapters();
        $this->session->destroy();

        return $this->response->redirect($this->router->getRouteByName('home'));
    }

    // -----------------------------------------------------------------------------

    /**
     * @return string JSON
     */
    public function passwordForgotAction(): Response
    {
        $email = $this->request->getPost('email');
        $user  = User::findFirstByEmail($email);

        if ( ! $user) {
            return $this->output(0, 'No email associated.');
        }

        $user->password_reset_key        = hash('sha512', time() * random_int(1, 9999));
        $user->password_reset_expires_at = date('Y-m-d H:i:s', strtotime('+10 minutes'));
        $user->update();

        if ($user->getMessages()) {
            return $this->output(0, 'An internal update to the user occurred.');
        }

        // Email: Generate
        $content = $this->component->email->create('confirm-password-change', [
            'reset_link' => \Library\Url::get('user/passwordcreate/' . $user->password_reset_key),
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
        if ( ! in_array($mail_result->statusCode(), [200, 201, 202])) {
            return $this->output(0, 'There was a problem sending the email.');
        }

        return $this->output(0, 'A reset link has been sent to your email.
            You have 10 minutes to change your
            password before the link expires.');
    }

    // -----------------------------------------------------------------------------

    /**
     * @return string JSON
     */
    public function passwordForgotCreateAction(): Response
    {
        $confirmEmail = $this->request->getPost('email');
        $resetKey     = $this->request->getPost('reset_key');

        $user = User::findFirst([
            "email = :email: AND password_reset_key = :key: AND password_reset_expires_at > :date:",
            "bind" => [
                "email" => $confirmEmail,
                "key"   => $resetKey,
                "date"  => getDateTime(),
            ],
        ]);

        if ( ! $user) {
            return $this->output(0, 'Invalid email and key combo, or time has expired.');
        }

        $password         = $this->request->getPost('password');
        $confirm_password = $this->request->getPost('confirm_password');

        if ($password != $confirm_password) {
            return $this->output(0, 'Your passwords do not match.');
        }

        // Create the new password, set a new salt and reset key
        $user->password                  = $this->security->hash($password);
        $user->password_salt             = $this->security->hash(random_int(5000, 100000));
        $user->password_reset_key        = null;
        $user->password_reset_expires_at = null;
        $user->save();

        if ($user->getMessages()) {
            return $this->output(0, 'There was an internal error updating.');
        }

        return $this->output(1, 'Your password has changed, please login.');
    }

    // -----------------------------------------------------------------------------

    /**
     * Creates a User Session
     *
     * @param \User  $user        User Model
     * @param string $accountType The login they signed in with
     * @param array  $additional  Additional values to add to session
     *
     * @return void
     */
    protected function createSession(\User $user, $accountType, array $additional = []): void
    {
        // Clear the login attempts
        $user->login_attempt    = null;
        $user->login_attempt_at = null;

        $this->session->set('is_logged_in', true);
        $this->session->set('id', $user->id);
        $this->session->set('role', $user->role);
        $this->session->set('alias', $user->getAlias());

        // What did they signin with?
        $this->session->set('auth_type', $accountType);

        $use_timezone = 'utc';
        if (property_exists($user, 'timezone')) {
            $use_timezone = $user->timezone;
        }

        $this->session->set('timezone', $use_timezone);

        if (is_array($additional)) {
            foreach ($additional as $_key => $_value) {
                $this->session->set($_key, $_value);
            }
        }

        // Delete old session so multiple logins aren't allowed
        session_regenerate_id(true);

        $user->session_id = $this->session->getId();
        $user->save();

        // If the user changes web browsers, prevent a hijacking attempt
        $this->session->set('agent', $_SERVER[ 'HTTP_USER_AGENT' ]);
    }

}

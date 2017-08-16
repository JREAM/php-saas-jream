<?php
namespace Dashboard;
use \Phalcon\Tag;

class AccountController extends \BaseController
{

    const REDIRECT_SUCCESS = "dashboard/account";
    const REDIRECT_FAILURE = "dashboard/account";
    const REDIRECT_DELETE = "dashboard/account/delete";
    const REDIRECT_LOGOUT = "user/logout";

    // --------------------------------------------------------------

    /**
     * @return void
     */
    public function onConstruct()
    {
        parent::initialize();
        Tag::setTitle('Account | ' . $this->di['config']['title']);
    }

    // --------------------------------------------------------------

    /**
     * @return void
     */
    public function indexAction()
    {
        $this->view->setVars([
            'changeEmailForm'    => new \ChangeEmailForm(),
            'changePasswordForm' => new \ChangePasswordForm(),
            'user'               => \User::findFirstById($this->session->get('id')),
            'purchases'          => \UserPurchase::findByUserId($this->session->get('id')),
            'timezones'          => \DateTimeZone::listIdentifiers(),
            'tokenKey'           => $this->security->getTokenKey(),
            'token'              => $this->security->getToken()
        ]);

        $this->view->pick("dashboard/account");
    }

    // --------------------------------------------------------------

    /**
     * @return void
     */
    public function deleteAction()
    {
        $this->view->setVars([
            'user'     => \User::findFirstById($this->session->get('id')),
            'tokenKey' => $this->security->getTokenKey(),
            'token'    => $this->security->getToken()
        ]);

        $this->view->pick("dashboard/account-delete");
    }

    // --------------------------------------------------------------

    /**
     * @return void
     */
    public function doDeleteAction()
    {
        $this->view->disable();
        $this->component->helper->csrf(self::REDIRECT_FAILURE . '/delete');

        $confirm = $this->request->getPost('confirm');
        $understand = $this->request->getPost('understand');

        if ($understand != 'on') {
            $this->flash->error("You must check off the box for understanding your account removal.");
            return $this->redirect(self::REDIRECT_DELETE);
        }

        $user = \User::findFirstById($this->session->get('id'));

        if (strtolower($confirm) != 'delete ' . strtolower($user->getAlias())) {
            $this->flash->error("To remove your account you must enter the confirmation text.");
            return $this->redirect(self::REDIRECT_DELETE);
        }

        $user->is_deleted = 1;
        $user->deleted_at = date('Y-m-d H:i:s', strtotime('now'));
        $result = $user->save();

        if (!$result) {
            $this->flash->error("There was a problem processing your request.");
            return $this->redirect(self::REDIRECT_DELETE);
        }

        // Hpw do i want to delete this?

        $this->flash->success("Sorry to see you go!");
        return $this->redirect(self::REDIRECT_LOGOUT);
    }

    // --------------------------------------------------------------

    /**
     * @return void
     */
    public function doTimezoneAction()
    {
        $this->view->disable();
        $this->component->helper->csrf(self::REDIRECT_FAILURE);

        $timezone = $this->request->getPost('timezone');
        if (!in_array($timezone, \DateTimeZone::listIdentifiers())) {
            $this->flash->error('Invalid Timezone');
            return $this->redirect(self::REDIRECT_SUCCESS);
        }

        $user = \User::findFirstById($this->session->get('id'));
        $user->timezone = $timezone;
        $user->save();

        // Set the timezone!
        $this->session->set('timezone', $timezone);

        $this->flash->success("Timezone updated");
        return $this->redirect(self::REDIRECT_SUCCESS);
    }

    // --------------------------------------------------------------

    /**
     * @return void
     */
    public function doEmailUpdateAction()
    {
        $this->view->disable();
        $this->component->helper->csrf(self::REDIRECT_FAILURE);

        $email = $this->request->getPost('email');
        $confirm_email = $this->request->getPost('confirm_email');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->flash->error('You must provide a valid email.');
            return $this->redirect(self::REDIRECT_SUCCESS);
        }

        if ($email != $confirm_email) {
            $this->flash->error('Your emails do not match.');
            return $this->redirect(self::REDIRECT_SUCCESS);
        }

        $emailExists = \User::findFirstByEmail($email);
        if ($emailExists) {
            $this->flash->error('This email is in use.');
            return $this->redirect(self::REDIRECT_SUCCESS);
        }

        $user = \User::findFirstById($this->session->get('id'));
        $user->email_change = $email;
        $user->email_change_key        = hash('sha512', $user->email . time());
        $user->email_change_expires_at = date('Y-m-d H:i:s', strtotime('+10 minutes'));
        $user->save();

        $content = $this->component->email->create('confirm-email-change', [
            'user_email_change' => $user->email_change,
            'change_url' => getBaseUrl('user/doConfirmEmailChange/' . $user->email_change_key)
        ]);

        if (!$content) {
            $this->flash->error('An internal error occured, we have been notified about it.');
            return $this->redirect(self::REDIRECT_SUCCESS);
        }

        $mail_result = $this->di->get('email', [
            [
                'to_name' => $user->getAlias($user->id),
                'to_email' => $user->getEmail($user->id),
                'from_name' => $this->config->email->from_name,
                'from_email' => $this->config->email->from_address,
                'subject' => 'JREAM Confirm Email Change',
                'content' => $content
            ]
        ]);

        if (! in_array($mail_result->statusCode(), [200, 201, 202])) {
            $text = 'There was a problem sending the email.';
            $this->flash->error($text);
        } else {
            $text = "Please verify your email change from the email sent to
                    ({$user->email}). You have 10 minutes to verify until
                    the link expires.";
            $this->flash->success($text);
        }

        return $this->redirect(self::REDIRECT_SUCCESS);
    }

    // --------------------------------------------------------------

    /**
     * @return void
     */
    public function doPasswordUpdateAction()
    {
        $this->view->disable();
        $this->component->helper->csrf(self::REDIRECT_FAILURE);

        $current_password = $this->request->getPost('current_password');
        $password = $this->request->getPost('password');
        $confirm_password = $this->request->getPost('confirm_password');

        if ($password != $confirm_password) {
            $this->flash->error('Your passwords do not match.');
            return $this->redirect(self::REDIRECT_SUCCESS);
        }

        if (strlen($password) < 4 || strlen($password) > 128) {
            $this->flash->error('Your password must be 4-128 characters.');
            return $this->redirect(self::REDIRECT_SUCCESS);
        }

        $user = \User::findFirstById($this->session->get('id'));
        if (!$this->security->checkHash($current_password, $user->password)) {
            $this->flash->error('Your current password is incorrect.');
            return $this->redirect(self::REDIRECT_SUCCESS);
        }

        $user = \User::findFirstById($this->session->get('id'));
        $user->password = $this->security->hash($password);
        // Update Salt
        $user->password_salt = $this->security->hash(random_int(5000, 100000));
        $user->save();

        if ($user->getMessages()) {
            $this->flash->error($user->getMessagesList());
            return $this->redirect(self::REDIRECT_SUCCESS);
        }

        $this->flash->success('Your password has been changed.');
        return $this->redirect(self::REDIRECT_SUCCESS);

    }

    // --------------------------------------------------------------

    public function doEmailSettingsUpdateAction()
    {
        $this->view->disable();
        $this->component->helper->csrf(self::REDIRECT_FAILURE);

        $user = \User::findFirstById($this->session->get('id'));

        $user->email_notifications = (int) $this->request->getPost('email_notifications');
        $user->system_notifications = (int) $this->request->getPost('system_notifications');
        $user->newsletter_subscribe = (int) (bool) $this->request->getPost('newsletter_subscribe');

        $result = $user->save();

        if ($result) {
            $this->flash->success('Your Email settings have been updated.');
            return $this->redirect(self::REDIRECT_SUCCESS);
        }

        $this->flash->error($user->getMessagesString());
        return $this->redirect(self::REDIRECT_FAILURE);
    }

    // --------------------------------------------------------------

}

// End of File
// --------------------------------------------------------------

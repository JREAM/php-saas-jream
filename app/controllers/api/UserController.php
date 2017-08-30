<?php
declare(strict_types=1);

namespace Controllers\Api;

class UserController extends ApiController
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
    public function updateTimezoneAction()
    {
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

    // -----------------------------------------------------------------------------

    /**
     * @return string JSON
     */
    public function updateEmailAction()
    {
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

    // -----------------------------------------------------------------------------

    /**
     * @return string JSON
     */
    public function updateEmailConfirmAction($resetKey)
    {
        $user = \User::findFirst([
            "email_change_key = :key: AND email_change_expires_at > :date:",
            "bind" => [
                "key"  => $resetKey,
                "date" => getDateTime(),
            ],
        ]);

        if (!$user) {
            return $this->output(0, 'Invalid key, or time has expired.');
        }

        $user->email = $user->email_change;
        $user->email_change = null;
        $user->email_change_key = null;
        $user->email_change_expires_at = null;
        $user->save();

        if ($user->getMessages() == false) {
            return $this->output(1, 'Confirmed. Email has been changed, please re-login using your new email.');
        }

        return $this->output(0, $user->getMessagesList());
    }

    // -----------------------------------------------------------------------------

    /**
     * @return string JSON
     */
    public function updateNotificationsAction()
    {
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

    // -----------------------------------------------------------------------------

    /**
     * @return string JSON
     */
    public function updatePasswordAction()
    {
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

    // -----------------------------------------------------------------------------

    /**
     * @return void
     */
    public function deleteAccountAction()
    {
        $confirm = $this->request->getPost('confirm');
        $understand = $this->request->getPost('understand');

        if ($understand != 'on') {
            return $this->output(0, "You must check off the box for understanding your account removal.");
        }

        $user = \User::findFirstById($this->session->get('id'));

        if (strtolower($confirm) != 'delete ' . strtolower($user->getAlias())) {
            return $this->output(0, "To remove your account you must enter the confirmation text.");
        }

        $user->is_deleted = 1;
        $user->deleted_at = date('Y-m-d H:i:s', strtotime('now'));
        $result = $user->save();

        if (!$result) {
            return $this->output(0, "There was a problem processing your request.");
        }

        // Hpw do i want to delete this?
        return $this->output(0, "Sorry to see you go!");
        $this->session->destroy(); // @TODO: How to destroy all session?
    }

}

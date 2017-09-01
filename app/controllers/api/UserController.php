<?php
declare(strict_types=1);

namespace Controllers\Api;

use Phalcon\Http\Response;

class UserController extends ApiController
{

    public function onConstruct()
    {
        parent::initialize();
    }

    // -----------------------------------------------------------------------------

    /**
     * @return Response
     */
    public function updateTimezoneAction() : Response
    {
        $timezone = $this->request->getPost('timezone');
        if (!in_array($timezone, \DateTimeZone::listIdentifiers())) {
            return $this->output(0, 'Invalid Timezone');
        }

        $user = \User::findFirstById($this->session->get('id'));
        $user->timezone = $timezone;
        $user->save();

        // Set the timezone!
        $this->session->set('timezone', $timezone);

        return $this->output(1, "Timezone updated");
    }

    // -----------------------------------------------------------------------------

    /**
     * @return Response
     */
    public function updateEmailAction() : Response
    {
        $email = $this->request->getPost('email');
        $confirm_email = $this->request->getPost('confirm_email');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->output(0, 'You must provide a valid email.');
        }

        if ($email != $confirm_email) {
            return $this->output(0, 'Your emails do not match.');
        }

        $emailExists = \User::findFirstByEmail($email);
        if ($emailExists) {
            return $this->output(0, 'This email is in use.');
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
            return $this->output(0, 'An internal error occured, we have been notified about it.');
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
            return $this->output(0, 'There was a problem sending the email.');
        }

        return $this->output(1, "Please verify your email change 
            from the email sent to ({$user->email}). You have 10 minutes to verify 
            until the link expires."
        );
    }

    // -----------------------------------------------------------------------------

    /**
     * @return Response
     */
    public function updateEmailConfirmAction(string $resetKey) : Response
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
     * @return Response
     */
    public function updateNotificationsAction() : Response
    {
        $user = \User::findFirstById($this->session->get('id'));

        $user->email_notifications = (int) $this->request->getPost('email_notifications');
        $user->system_notifications = (int) $this->request->getPost('system_notifications');
        $user->newsletter_subscribe = (int) (bool) $this->request->getPost('newsletter_subscribe');

        $result = $user->save();

        if ($result) {
            return $this->output(0, 'Your Email settings have been updated.');
        }

        return $this->output(0, $user->getMessagesString());
    }

    // -----------------------------------------------------------------------------

    /**
     * @return Response
     */
    public function updatePasswordAction() : Response
    {
        $current_password = $this->request->getPost('current_password');
        $password = $this->request->getPost('password');
        $confirm_password = $this->request->getPost('confirm_password');

        if ($password != $confirm_password) {
            return $this->output(0,'Your passwords do not match.');
        }

        if (strlen($password) < 4 || strlen($password) > 128) {
            return $this->output(0,'Your password must be 4-128 characters.');
        }

        $user = \User::findFirstById($this->session->get('id'));
        if (!$this->security->checkHash($current_password, $user->password)) {
            return $this->output(0,'Your current password is incorrect.');
        }

        $user = \User::findFirstById($this->session->get('id'));
        $user->password = $this->security->hash($password);
        // Update Salt
        $user->password_salt = $this->security->hash(random_int(5000, 100000));
        $user->save();

        if ($user->getMessages()) {
            return $this->output(0, $user->getMessagesList());
        }

        return $this->output(1, 'Your password has been changed');
    }

    // -----------------------------------------------------------------------------

    /**
     * @return Response
     */
    public function deleteAccountAction() : Response
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

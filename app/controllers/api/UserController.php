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

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * @return Response
     */
    public function updateTimezoneAction(): Response
    {
        $this->apiMethods(['POST']);

        $this->json = $this->request->getJsonRawBody();
        $timezone = $this->json->timezone;
        if (!in_array($timezone, \DateTimeZone::listIdentifiers())) {
            return $this->output(0, 'Invalid Timezone');
        }

        $user           = \User::findFirstById($this->session->get('id'));
        $user->timezone = $timezone;
        $user->save();

        // Set the timezone!
        $this->session->set('timezone', $timezone);

        return $this->output(1, "Timezone updated", [
            'timezone' => $timezone
        ]);
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * @return Response
     */
    public function updateEmailAction(): Response
    {
        $this->apiMethods(['POST']);

        $this->json = $this->request->getJsonRawBody();

        $email         = $this->json->email;
        $confirmEmail = $this->json->confirm_email;

        $form = new \Forms\ChangeEmailForm(null, ['email' => $email]);

        if (!$form->isValid($this->json) && count($form->getMessages()) > 0) {
            return $this->output(0, $form->getMessages());
        }

        $emailExists = \User::findFirstByEmail($email);
        if ($emailExists) {
            return $this->output(0, 'This email is in use.');
        }

        $user                          = \User::findFirstById($this->session->get('id'));
        $user->email_change            = $email;
        $user->email_change_key        = hash('sha512', $user->email . time());
        $user->email_change_expires_at = date('Y-m-d H:i:s', strtotime('+10 minutes'));
        $user->save();

        // @TODO FIX EMAIL, USE FAKE EMAILING SOON
        //$content = $this->component->email->create('confirm-email-change', [
        //    'user_email_change' => $user->email_change,
        //    'change_url'        => \Library\Url::get('user/doConfirmEmailChange/' . $user->email_change_key),
        //]);
        //
        //if (!$content) {
        //    return $this->output(0, 'An internal error occured, we have been notified about it.');
        //}
        //
        //$mailResult = $this->di->get('email', [
        //    [
        //        'to_name'    => $user->getAlias($user->id),
        //        'to_email'   => $user->getEmail($user->id),
        //        'from_name'  => $this->config->email->from_name,
        //        'from_email' => $this->config->email->from_address,
        //        'subject'    => 'JREAM - Confirm Email Change',
        //        'content'    => $content,
        //    ],
        //]);
        //
        //if (!in_array($mailResult->statusCode(), [200, 201, 202], true)) {
        //    return $this->output(0, 'There was a problem sending the email.');
        //}

        return $this->output(1, "Please verify your email change from the email sent to ({$user->email}). 
            You have 10 minutes to verify until the link expires.", ['email' => $email]);
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * @return Response
     */
    public function updateEmailConfirmAction(string $resetKey): Response
    {
        $this->apiMethods(['GET']);
        $this->json = $this->request->getJsonRawBody();

        //$form = new \Forms\ChangeEmailForm(null, ['email' => $this->json->email')]);
        //if (!$form->isValid($this->json) && count($form->getMessages()) > 0) {
        //    return $this->output(0, $form->getMessages());
        //}

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

        $user->email                   = $user->email_change;
        $user->email_change            = null;
        $user->email_change_key        = null;
        $user->email_change_expires_at = null;
        $user->save();

        if ($user->getMessages() == false) {
            return $this->output(1, 'Confirmed. Email has been changed, please re-login using your new email.', [
                'email' => $user->email
            ]);
        }

        return $this->output(0, $user->getMessagesAsHTML());
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * Update the users Alias (displayed name)
     *
     * @return \Phalcon\Http\Response
     */
    public function updateAliasAction(): Response
    {
        $this->apiMethods(['POST']);
        $this->json = $this->request->getJsonRawBody();

        $user = \User::findFirstById($this->session->get('id'));
        $alias = (string) $this->json->alias;

        $form = new \Forms\ChangeAliasForm(null, ['alias' => $alias]);

        if (!$form->isValid($this->json) && count($form->getMessages()) > 0) {
            return $this->output(0, $form->getMessages());
        }

        // @TODO: Add rules, no "Admin, JREAM, special chars, in names, no taken names
        $user->alias = (string) $alias;
        $result = $user->save();

        if ($result) {
            return $this->output(0, 'Your Alias has been updated.', ['alias' => $alias]);
        }

        return $this->output(0, $user->getMessagesString());
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * @return Response
     */
    public function updateNotificationsAction(): Response
    {
        $this->apiMethods(['POST']);

        $this->json = $this->request->getJsonRawBody();

        $user = \User::findFirstById($this->session->get('id'));

        $user->email_notifications  = (int) $this->json->email_notifications;
        $user->system_notifications = (int) $this->json->system_notifications;
        $user->newsletter_subscribe = (int) (bool) $this->json->newsletter_subscribe;

        $result = $user->save();

        if ($result) {
            return $this->output(0, 'Your Email settings have been updated.', [
                'email_notifications' => $user->email_notifications,
                'newsletter_subscribe' => $user->system_notifications,
                'email_subscribe' => $user->newsletter_subscribe
            ]);
        }

        return $this->output(0, $user->getMessagesString());
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * @return Response
     */
    public function updatePasswordAction(): Response
    {
        $this->apiMethods(['POST']);
        $this->json = $this->request->getJsonRawBody();

        $current_password = $this->json->current_password;
        $password         = $this->json->password;
        $confirm_password = $this->json->confirm_password;

        $form = new \Forms\ChangePasswordForm(null, ['confirm_password' => $confirm_password]);

        if (!$form->isValid($this->json) && count($form->getMessages()) > 0) {
            return $this->output(0, $form->getMessages());
        }

        $user = \User::findFirstById($this->session->get('id'));
        if (!$this->security->checkHash($current_password, $user->password)) {
            return $this->output(0, 'Your current password is incorrect.');
        }

        $user           = \User::findFirstById($this->session->get('id'));
        $user->password = $this->security->hash($password);
        // Update Salt
        $user->password_salt = $this->security->hash(random_int(5000, 100000));
        $user->save();

        if ($user->getMessages()) {
            return $this->output(0, $user->getMessagesAsHTML());
        }

        return $this->output(1, 'Your password has been changed', [
            'password' => '<private>'
        ]);
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * @return Response
     */
    public function deleteAccountAction(): Response
    {
        $this->apiMethods(['POST']);
        $confirm    = $this->json->confirm;
        $understand = (in_array($this->json->understand, ['on', 1])) ? 1 : 0;

        if (!$understand) {
            return $this->output(0, "You must check off the box for understanding your account removal.");
        }

        $user = \User::findFirstById($this->session->get('id'));

        if (strtolower($confirm) !== 'delete') {
            // @TODO Whats the getAlias attac hed to this for?
            //if (strtolower($confirm) !== 'delete ' . strtolower($user->getAlias())) {
            return $this->output(0, "To remove your account you must enter the confirmation text.");
        }

        $user->is_deleted = 1;
        $user->deleted_at = date('Y-m-d H:i:s', strtotime('now'));
        $result           = $user->save();

        if (!$result) {
            return $this->output(0, "There was a problem processing your request.");
        }

        // Hpw do i want to delete this?
        $this->session->destroy(); // @TODO: How to destroy all session?
        return $this->output(0, "Sorry to see you go!", [
            'id' => $user->id
        ]);
    }
}

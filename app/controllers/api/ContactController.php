<?php

namespace Controllers\Api;

use Phalcon\Http\Response;
use Phalcon\Mvc\Controller;
use Library\Recaptcha;

class Contact extends Controller
{

    /**
     * @return Response
     */
    public function sendAction() : Response
    {
        // If Recaptcha fails, Warn and use JS to reload.
        if (!new Recaptcha($this->session, $this->request->getPost('g-recaptcha-response')) ) {
            // Retrigger: grecaptcha.reset() in JS
            return $this->output(0, 'Recaptcha is invalid, please try again.');
        }

        // Make sure recaptcha called and all
        if( ! $this->session->has('recaptcha')) {
            return $this->output(0, 'Recaptcha is required.');
        }

        if( ! $this->session->get('recaptcha')) {
            return $this->output(0, 'Recaptcha was invalid');
        }

        $form = new \Forms\ContactForm();

        // Make sure the form is valid
        if( ! $form->isValid($_POST)) {

            $errors = [];
            foreach($form->getMessages() as $message) {
                $errors[] = $message->getMessage();
            }

            return $this->output(0, $errors);
        }

        // Gather the POST stuff
        $email     = $this->request->getPost('email');
        $message   = $this->request->getPost('message');
        $name      = $this->request->getPost('name');
        $recatcha = $this->request->getPost('g-recaptcha-response');

        // if ()

        // Create the Message from a template
        $content = $this->component->email->create('contact', [
            'name'    => $name,
            'email'   => $email,
            'message' => $message,
        ]);

        $mail_result = $this->di->get('email', [
            [
                'to_name'    => 'JREAM',
                'to_email'   => 'hello@jream.com',
                'from_name'  => $name,
                'from_email' => $email,
                'subject'    => 'JREAM Contact Form',
                'content'    => $content,
            ],
        ]);

        if( ! in_array($mail_result->statusCode(), [200, 201, 202], true)) {
            return $this->output(0, 'Error sending email');
        }

        // Succcess
        $this->session->set('recaptcha', 0);
        return $this->output(1, 'Email Sent');
    }

}

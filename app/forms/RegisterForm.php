<?php

namespace App\Forms;

use Phalcon\Forms\Element;
use Phalcon\Validation\Validator;

class RegisterForm extends \Phalcon\Forms\Form
{

    public function initialize()
    {
        $alias = new Element\Text('alias', [
            'placeholder' => 'Alias',
            'class'       => 'form-control input-lg',
        ]);

        $email = new Element\Text('email', [
            'placeholder' => 'Email',
            'class'       => 'form-control input-lg',
        ]);

        $email->addValidators([
            new Validator\PresenceOf([
                'message' => 'Your email is required.',
            ]),
            new Validator\Email([
                'message' => 'Your email is not valid.',
            ]),
        ]);

        $password = new Element\Password('password', [
            'placeholder' => 'Password',
            'class'       => 'form-control input-lg',
        ]);

        $password->addValidators([
            new Validator\PresenceOf([
                'message' => 'Your email is required.',
            ]),
        ]);

        $confirmPassword = new Element\Password('confirm_password', [
            'placeholder' => 'Confirm Password',
            'class'       => 'form-control input-lg',
        ]);

//        $newsletterSubscribe = new Element\Check('newsletter_subscribe', [
//            'value'       => 1,
//            'class'       => '',
//        ]);
//        $newsletterSubscribe->setLabel('Subscribe to Newsletter');

        $this->add($alias);
        $this->add($email);
        $this->add($password);
        $this->add($confirmPassword);
//        $this->add($newsletterSubscribe);

        $this->add(new Element\Submit('submit', [
            'value' => 'Register',
            'class' => 'btn btn-lg btn-primary btn-block',
        ]));
    }

    // --------------------------------------------------------------
}

<?php
declare(strict_types=1);

namespace Forms;

use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Submit;
use Phalcon\Validation\Validator;

class RegisterForm extends BaseForm
{

    public function initialize()
    {
        $alias = new Text('alias', [
            'placeholder' => 'Alias',
            'class'       => 'form-control input-lg',
        ]);

        $alias->addValidators([
            new Validator\StringLength([
                "max"            => 18,
                "min"            => 5,
                "messageMaximum" => "Your alias must be less than or equal to 18 characters.",
                "messageMinimum" => "Your alias must be atleast 5 characters",
            ]),
        ]);


        $email = new Text('email', [
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

        $password = new Password('password', [
            'placeholder' => 'Password',
            'class'       => 'form-control input-lg',
        ]);

        $password->addValidators([
            new Validator\PresenceOf([
                'message' => 'Your password is required.',
            ]),
            new Validator\StringLength([
                "max"            => 48,
                "min"            => 6,
                "messageMaximum" => "Your password must be less than or equal to 48 characters.",
                "messageMinimum" => "Your password must be atleast 6 characters",
            ]),
        ]);

        $confirmPassword = new Password('confirm_password', [
            'placeholder' => 'Confirm Password',
            'class'       => 'form-control input-lg',
        ]);

        $confirmPassword->addValidators([
            new Validator\PresenceOf([
                'message' => 'Confirm Password is required.',
            ]),
        ]);

        $this->add($alias);
        $this->add($email);
        $this->add($password);
        $this->add($confirmPassword);
//        $this->add($newsletterSubscribe);

        $this->add(new Submit('submit', [
            'value' => 'Register',
            'class' => 'btn btn-lg btn-primary btn-block',
        ]));
    }

    // --------------------------------------------------------------
}

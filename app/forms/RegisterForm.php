<?php

declare(strict_types=1);

namespace Forms;

use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Submit;
use Phalcon\Validation\Validator;
use \Phalcon\Di\FactoryDefault as Di;

class RegisterForm extends BaseForm
{

    public function initialize(): void
    {
        // Faker Data is used to save time going through areas manually
        $di        = Di::getDefault();
        $fakerData = $di->get('fakerData');

        $alias = new Text('alias', [
            'placeholder' => 'Alias',
            'class'       => 'form-control input-lg',
            'value'       => $fakerData->alias,
        ]);
        $alias->setFilters(['string', 'trim',]);

        $alias->addValidators([
            new Validator\StringLength([
                "min"            => 5,
                "max"            => 18,
                "messageMinimum" => "Your alias must be at least 5 characters",
                "messageMaximum" => "Your alias must be less than or equal to 18 characters.",
            ]),
            new Validator\Alpha([
                'message' => 'Your alias must be alphabetical only',
            ]),
        ]);


        $email = new Text('email', [
            'placeholder' => 'Email',
            'class'       => 'form-control input-lg',
            'value'       => $fakerData->email,
        ]);
        $email->setFilters(['email', 'trim',]);

        $email->addValidators([
            new Validator\PresenceOf([
                'message' => 'Your email is required.',
            ]),
            new Validator\Email([
                'message' => 'Your email is not valid.',
            ]),
        ]);

        $fakerPassword = $fakerData->password;

        $password = new Password('password', [
            'placeholder' => 'Password',
            'class'       => 'form-control input-lg',
            'value'       => $fakerPassword,
        ]);
        $password->setFilters(['string', 'trim',]);

        $password->addValidators([
            new Validator\PresenceOf([
                'message' => 'Your password is required.',
            ]),
            new Validator\StringLength([
                "max"            => 48,
                "min"            => 6,
                "messageMaximum" => "Your password must be less than or equal to 48 characters.",
                "messageMinimum" => "Your password must be at least 6 characters",
            ]),
        ]);

        $confirmPassword = new Password('confirm_password', [
            'placeholder' => 'Confirm Password',
            'class'       => 'form-control input-lg',
            'value'       => &$fakerPassword,
        ]);
        $confirmPassword->setFilters(['string', 'trim',]);

        print_r($this);die;
        print_r($this->getValue('alias'));
        print_r($this->getValue('password'));

        echo $this->getUserOption('confirm_password');
        die;
        $confirmPassword->addValidators([
            new Validator\PresenceOf([
                'message' => 'Confirm Password is required.',
            ]),
            new Validator\Identical([
                'accepted' => $this->getUserOption('confirm_password'),
                'message'  => 'Your passwords must match.',
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

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
}

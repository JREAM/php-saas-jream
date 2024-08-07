<?php

declare(strict_types=1);

namespace Forms;

use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator;

class LoginForm extends BaseForm
{

    /**
     * Initialize the Form
     *
     * @return void  Handled by Phalcon
     */
    public function initialize(): void
    {
        $email = new Text('email', [
            'placeholder' => 'Email',
            'class'       => 'form-control input-lg',
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

        $password = new Password('password', [
            'placeholder' => 'Password',
            'class'       => 'form-control input-lg',
        ]);
        $password->setFilters(['string', 'trim',]);

        $password->addValidators([
            new Validator\PresenceOf([
                'message' => 'Your password is required.',
            ]),
            new Validator\StringLength([
                'min'        => 5,
                'minMessage' => 'Your password must be at least 5 characters',
            ]),
        ]);

        $this->add($email);
        $this->add($password);

        $this->add(new Submit('submit', [
            'value' => 'Login',
            'class' => 'btn btn-lg btn-primary btn-block',
        ]));
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
}

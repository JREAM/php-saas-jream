<?php

declare(strict_types=1);

namespace Forms;

use /** @noinspection PhpUndefinedClassInspection */
    Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Submit;
use Phalcon\Validation\Validator;

class ChangePasswordForm extends BaseForm
{

    public function initialize(): void
    {
        $currentPassword = new Text('current_password', [
            'placeholder' => 'Current Password',
            'class'       => 'form-control input-lg',
        ]);

        $password = new Text('password', [
            'placeholder' => 'New Password',
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
                "messageMinimum" => "Your password must be at least 6 characters",
            ]),
        ]);

        $confirmPassword = new Text('confirm_password', [
            'placeholder' => 'Confirm New Password',
            'class'       => 'form-control input-lg',
        ]);

        $confirmPassword->addValidators([
            new Validator\PresenceOf([
                'message' => 'Confirm Password is required.',
            ]),
            new Validator\Identical([
                'accepted' => $this->getUserOption('password'),
                'message'  => 'Your passwords must match.',
            ]),
        ]);


        $this->add($currentPassword);
        $this->add($password);
        $this->add($confirmPassword);

        $this->add(new Submit('submit', [
            'value' => 'Submit',
            'class' => 'btn btn-lg btn-primary btn-block',
        ]));
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
}

<?php

namespace App\Forms;

use Phalcon\Forms\Element;
use Phalcon\Validation\Validator;

class ChangePasswordForm extends \Phalcon\Forms\Form
{

    public function initialize()
    {
        $current_password = new Element\Text('current_password', [
            'placeholder' => 'Current Password',
            'class'       => 'form-control input-lg',
        ]);

        $password = new Element\Text('password', [
            'placeholder' => 'New Password',
            'class'       => 'form-control input-lg',
        ]);

        $confirm_password = new Element\Text('confirm_password', [
            'placeholder' => 'Confirm New Password',
            'class'       => 'form-control input-lg',
        ]);

        $this->add($current_password);
        $this->add($password);
        $this->add($confirm_password);

        $this->add(new Element\Submit('submit', [
            'value' => 'Submit',
            'class' => 'btn btn-lg btn-primary btn-block',
        ]));
    }

    // --------------------------------------------------------------
}

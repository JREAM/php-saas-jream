<?php
declare(strict_types=1);

use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Submit;
use Phalcon\Validation\Validator;

class ChangePasswordForm extends \Phalcon\Forms\Form
{

    public function initialize()
    {
        $current_password = new Text('current_password', [
            'placeholder' => 'Current Password',
            'class'       => 'form-control input-lg',
        ]);

        $password = new Text('password', [
            'placeholder' => 'New Password',
            'class'       => 'form-control input-lg',
        ]);

        $confirm_password = new Text('confirm_password', [
            'placeholder' => 'Confirm New Password',
            'class'       => 'form-control input-lg',
        ]);

        $this->add($current_password);
        $this->add($password);
        $this->add($confirm_password);

        $this->add(new Submit('submit', [
            'value' => 'Submit',
            'class' => 'btn btn-lg btn-primary btn-block',
        ]));
    }

    // --------------------------------------------------------------
}

<?php

namespace App\Forms;

use Phalcon\Forms\Element;
use Phalcon\Validation\Validator;

class ChangeEmailForm extends \Phalcon\Forms\Form
{

    public function initialize()
    {
        $email = new Element\Text('email', [
            'placeholder' => 'New Email',
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


        $confirm_email = new Element\Text('confirm_email', [
            'placeholder' => 'Confirm New Email',
            'class'       => 'form-control input-lg',
        ]);

        $confirm_email->addValidators([
            new Validator\PresenceOf([
                'message' => 'Your email is required.',
            ]),
            new Validator\Email([
                'message' => 'Your email is not valid.',
            ]),
        ]);

        $this->add($email);
        $this->add($confirm_email);

        $this->add(new Element\Submit('submit', [
            'value' => 'Submit',
            'class' => 'btn btn-lg btn-primary btn-block',
        ]));
    }

    // --------------------------------------------------------------
}

<?php

use Phalcon\Forms\Element;
use Phalcon\Validation\Validator;

class CheckoutForm extends \Phalcon\Forms\Form
{

    public function initialize()
    {
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

        $this->add($email);

        $this->add(new Element\Submit('submit', [
            'value' => 'Purchase',
            'class' => 'btn btn-lg btn-primary btn-block',
        ]));
    }

    // --------------------------------------------------------------
}

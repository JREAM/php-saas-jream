<?php

namespace App\Forms;

use Phalcon\Forms\Element;
use Phalcon\Validation\Validator;

class ContactForm extends \Phalcon\Forms\Form
{

    public function initialize()
    {

        $name = new Element\Text('name', [
            'placeholder' => 'Name',
            'class'       => 'form-control input-lg',
        ]);

        $name->addValidators([
            new Validator\PresenceOf([
                'message' => 'Your name is required.',
            ]),
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

        $msg = new Element\TextArea('message', [
            'placeholder' => 'Message',
            'class'       => 'form-control input-lg',
        ]);

        $msg->addValidators([
            new Validator\PresenceOf([
                'message' => 'Your message is required.',
            ]),
        ]);

        $this->add($name);
        $this->add($email);
        $this->add($msg);

        $this->add(new Element\Submit('submit', [
            'value' => 'Send the Email',
            'class' => 'btn btn-lg btn-primary btn-block',
        ]));
    }

    // --------------------------------------------------------------
}

<?php

declare(strict_types=1);

namespace Forms;

use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Element\Submit;
use Phalcon\Validation\Validator;

class ContactForm extends BaseForm
{

    /**
     * Init Form
     *
     * @return void
     */
    public function initialize(): void
    {
        $name = new Text('name', [
            'placeholder' => 'Name',
            'class'       => 'form-control input-lg',
        ]);
        $name->setFilters(['string', 'trim',]);

        $name->addValidators([
            new Validator\PresenceOf([
                'message' => 'Your name is required.',
            ]),
            new Validator\StringLength([
                'min' => 1,
                'max' => 255,
                '',
            ]),
        ]);

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

        $msg = new TextArea('message', [
            'placeholder' => 'Message',
            'class'       => 'form-control input-lg',
        ]);
        $msg->setFilters(['string', 'trim',]);

        $msg->addValidators([
            new Validator\PresenceOf([
                'message' => 'Your message is required.',
            ]),
        ]);

        $this->add($name);
        $this->add($email);
        $this->add($msg);

        $this->add(new Submit('submit', [
            'value' => 'Send the Email',
            'class' => 'btn btn-lg btn-primary btn-block',
        ]));
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
}

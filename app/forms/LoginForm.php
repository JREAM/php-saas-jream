<?php
use Phalcon\Forms\Element;
use Phalcon\Validation\Validator;

class LoginForm extends \Phalcon\Forms\Form
{

    public function initialize()
    {
        $email = new Element\Text('email', [
            'placeholder' => 'Email',
            'class' => 'form-control input-lg'
        ]);

        $email->addValidators([
            new Validator\PresenceOf([
                'message' => 'Your email is required.'
            ]),
            new Validator\Email([
                'message' => 'Your email is not valid.'
            ])
        ]);


        $password = new Element\Password('password', [
            'placeholder' => 'Password',
            'class' => 'form-control input-lg'
        ]);

        $password->addValidators([
            new Validator\PresenceOf([
                'message' => 'Your email is required.'
            ])
        ]);

        $this->add($email);
        $this->add($password);

        $this->add(new Element\Submit('submit', [
            'value' => 'Login',
            'class' => 'btn btn-lg btn-primary btn-block'
        ]));

    }

    // --------------------------------------------------------------

}

// End of File
// --------------------------------------------------------------
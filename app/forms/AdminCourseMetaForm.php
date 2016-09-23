<?php
use Phalcon\Forms\Element;
use Phalcon\Validation\Validator;

class AdminCourseMetaForm extends \Phalcon\Forms\Form
{

    public function initialize()
    {
        $email = new Element\Text('email', [
            'placeholder' => 'Email',
            'class' => 'form-control input-lg'
        ]);

        $this->add($email);
    }

    // --------------------------------------------------------------

}

// End of File
// --------------------------------------------------------------
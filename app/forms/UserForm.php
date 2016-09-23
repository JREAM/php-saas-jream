<?php
use Phalcon\Forms\Element;
use Phalcon\Validation\Validator;

class UserForm extends \Phalcon\Forms\Form
{

    /**
     * Create a form
     *
     * @usage:
     * $form = new UserForm(new Users(), array('edit' => true));
     *
     * @param  User|boolean $user
     * @param  string $option [description]
     *
     * @return Form object
     */
    public function initialize($user, $options)
    {
        if ($option['edit'])
        {
            $this->add(new Element\Hidden('id'));
            $this->add(new Element\Text('alias'));
            $this->add(new Element\Email('email'));
            $this->add(new Element\Text('facebook_alias'));
            $this->add(new Element\Email('facebook_email'));
            $this->add(new Element\Password('password'));
        }
        else
        {
            $this->add(new Element\Text('id'));
            $this->add(new Element\Text('alias'));
            $this->add(new Element\Email('email'));
            $this->add(new Element\Text('facebook_alias'));
            $this->add(new Element\Email('facebook_email'));
            $this->add(new Element\Password('password'));
        }

        $this->add(new Element\Submit('submit', [
            'value' => 'Submit',
            'class' => 'btn btn-lg btn-primary btn-block'
        ]));
    }

    // --------------------------------------------------------------

}

// End of File
// --------------------------------------------------------------
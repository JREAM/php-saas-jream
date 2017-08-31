<?php
declare(strict_types=1);

namespace Forms;

use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator;

class PurchaseForm extends BaseForm
{

    public function initialize() : void
    {
        $name = new Text('name', [
            'placeholder' => 'Name on Card',
            'class'       => 'form-control',
            'value'       => formData('name'),
        ]);


        $card = new Text('password', [
            'placeholder' => 'New Password',
            'data-stripe' => 'number',
            'class'       => 'form-control',
            'value'       => (\APPLICATION_ENV == \APP_PRODUCTION) ? '' : '4242424242424242',
        ]);

        $expires = new Select('expires', [
            'class'       => 'form-control',
            'data-stripe' => 'exp-month',
        ]);

        $this->add($name);
        $this->add($card);
        $this->add($expires);

        $this->add(new Submit('submit', [
            'value' => 'Submit',
            'class' => 'btn btn-lg btn-primary btn-block',
        ]));
    }

    // --------------------------------------------------------------
}

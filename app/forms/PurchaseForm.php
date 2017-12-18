<?php

declare(strict_types=1);

namespace Forms;

use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator;

class PurchaseForm extends BaseForm
{

    /**
     * Initialize the Form
     *
     * @return void  Handled by Phalcon
     */
    public function initialize(): void
    {
        $name = new Text('name', [
            'placeholder' => 'Name on Card',
            'class'       => 'form-control',
            'value'       => formData('name'),
        ]);
        $name->setFilters(['string', 'trim',]);


        $card = new Text('number', [
            'placeholder' => 'Card Number',
            'data-stripe' => 'number',
            'class'       => 'form-control',
            'value'       => (\APPLICATION_ENV == \APP_PRODUCTION) ? '' : '4242424242424242',
        ]);
        $card->setFilters(['int', 'trim',]);

        $expires = new Select('expires', [
            'class'       => 'form-control',
            'data-stripe' => 'exp-month',
        ]);
        $expires->setFilters(['int', 'trim',]);

        $this->add($name);
        $this->add($card);
        $this->add($expires);

        $this->add(new Submit('submit', [
            'value' => 'Submit',
            'class' => 'btn btn-lg btn-primary btn-block',
        ]));
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
}

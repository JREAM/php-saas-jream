<?php

declare(strict_types=1);

namespace Forms;

use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Submit;
use Phalcon\Validation\Validator;

class ChangeAliasForm extends BaseForm
{

    /**
     * Initialize the Form
     *
     * @return void  Handled by Phalcon
     */
    public function initialize(): void
    {
        $alias = new Text('alias', [
            'placeholder' => 'New Alias',
            'maxlength'   => 12,
            'minlength'   => 4,
            'class'       => 'form-control input-lg',
        ]);

        $alias->addValidators([
            new Validator\PresenceOf([
                'message' => 'Your email is required.',
            ]),
            new Validator\StringLength([
                'min'        => 4,
                'minMessage' => 'Your alias be at least 4 characters',
                'max'        => 12,
                'maxMessage' => 'Your alias be a maximum of 12 characters',
            ]),
            new Validator\Alpha([
                'message' => 'Your alias can only contain letters.'
            ])
        ]);
        $alias->setFilters(['string', 'trim',]);

        $this->add($alias);

        $this->add(new Submit('submit', [
            'value' => 'Submit',
            'class' => 'btn btn-lg btn-primary btn-block',
        ]));
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
}

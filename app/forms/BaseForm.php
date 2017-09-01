<?php
declare(strict_types=1);

namespace Forms;

use Phalcon\Forms\Element;
use Phalcon\Validation\Validator;
use Phalcon\Forms\Form;

class BaseForm extends \Phalcon\Forms\Form
{

    /** The ID for the DOM Form */
    protected $_formId;

    public function initialize() : void
    {
    }

    // -----------------------------------------------------------------------------

    /**
     * Render the Entire Form is BS3
     *
     * @return void
     */
    public function fullRender() : void
    {
        // $form = new Form();
        echo "<form id='{$this->_formId}'>";

        $formElements = $this->getElements();

        // Loop through every form element
        foreach ($formElements as $key => $value) {
            $element = $this->get($key);

            // See if any errors pertain to the validation of this item
            $this->getMessagesFor($element->getName());
            if (count($messages)) {
                echo "<div class='alert alert-error'>";
                foreach ($messages as $msg) {
                    echo $this->flash->error($msg);
                }
                echo "</div>";
            }

            echo '<label for="' . $element->getName() . '">' . $element->getLabel() . '</label>';
            echo $element;
        }
        echo "</form>";
    }

    // -----------------------------------------------------------------------------
}

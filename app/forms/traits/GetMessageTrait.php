<?php

namespace Forms\Traits;

trait GetMessageTrait
{

    /**
     * Gets form messages as array
     *
     * @return array
     */
    public function getMessagesArray(): array
    {
        // Determine if this is a form, use t
        $useFieldKeys = false;
        if (method_exists($message, 'getField')) {
            $useFieldKeys = true;
        }

        $output = [];
        foreach ($this->getMessages() as $message) {
            $output[ $useFieldKeys ] = $message;
        }

        return $output;
    }
}

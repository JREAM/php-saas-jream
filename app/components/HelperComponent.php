<?php

use Phalcon\Mvc\User\Component;

/**
 * Helper Utilities
 *
 * Phalcon\Mvc\User\Component extends abstract class Phalcon\Di\Injectable
 */
class HelperComponent extends Component
{

    /**
     * Validate CSRF Tokens
     *
     * @param  boolean|string $redirectOnFailure (Optional) redirection
     *
     * @return boolean
     */
    public function csrf($redirectOnFailure = false, $isAjax = false)
    {
        if ($this->security->checkToken() == false)
        {
            // Only show a flash if its not an ajax call, otherwise use the boolean result.
            if (!$isAjax)
            {
                $this->flash->error('Invalid CSRF Token.');

                // Only redirect when supplied
                if ($redirectOnFailure)
                {
                    header('location: ' . getBaseUrl($redirectOnFailure));
                    exit;
                }
            }

            // Very Important for AJAX calls
            return false;
        }

        // Very Important for AJAX calls
        return true;
    }

}

<?php

namespace Library\Volt\Functions;

use Internationalization;
use Phalcon\Di;

class Translate extends Injectable
{

    /**
     * References Plugins\Internationalization files
     */
    public static function translate()
    {
        $di = Di::getDefault();

        if (isset(self::$_phrases[$key])) {
            $output = self::$_phrases[$key];
        }

        return $output ?: '';
    }

    // Alias for translate, tr is commonly used.
    public static function tr()
    {
        self::translate();
    }

    // -----------------------------------------------------------------------------
}

<?php

namespace Plugins;

use Internationalization;
use Phalcon\Di;

class VoltFunctions extends Injectable
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

    // -----------------------------------------------------------------------------

}

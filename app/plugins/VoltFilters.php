<?php

namespace Plugins;

use Phalcon\Di;

class VoltFilters
{

    /**
     * @param $arg
     */
    public static function uglify($arg)
    {
        $argument;
    }

    // -----------------------------------------------------------------------------

    /**
     * @param $arg
     *
     * @return string
     */
    public static function pretty($arg)
    {
        $padded    = sprintf('%08s', $arg);
        $formatted = substr($padded, 0, 2) . ':' . substr($padded, 2, 3) . ':' . substr($padded, 5, 3);

        return $formatted;
    }

    // -----------------------------------------------------------------------------

    /**
     * @param $arg
     *
     * @return mixed
     */
    public static function markdown($arg)
   {
       $di = Di::getDefault();
       $markdown = $di->get('markdown');

       return $markdown->parse($arg);
   }

   // -----------------------------------------------------------------------------

}

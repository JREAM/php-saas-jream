<?php

namespace Library\Volt\Filters;

use Phalcon\Di;

class Markdown
{

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

<?php

namespace Library\Volt\Tags;

use Phalcon\Tag;
use GK\JavascriptPacker;

class Uglify extends Tag
{

    public static function uglifyJs($arg)
    {
        $myPacker = new JavascriptPacker($arg, 'Normal', true, false);
        return $myPacker->pack();
    }

    // -----------------------------------------------------------------------------
}

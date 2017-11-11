<?php

namespace Plugins\Internationalization;

/**
 * Class En
 *
 * @package Plugins\Internationalization
 */
class En extends Base
{
    /**
     * Phrases, Generally we want to replace phrases rather than words since languages
     * won't properly handle a word-for-word translation.
     *
     * Always update this file first
     *
     * @var array All Default Languages in the Base File
     */
    protected static $phrases = [
        'name'    => 'overwrite here',
        'age'     => 'overwrite here',
        'profile' => 'overwrite here',
        'alias'   => 'overwrite here',
    ];

    // -----------------------------------------------------------------------------

    public function __construct()
    {
        foreach ($phrase as $key => $value) {
            $phrase;
        }
    }

    // -----------------------------------------------------------------------------
}

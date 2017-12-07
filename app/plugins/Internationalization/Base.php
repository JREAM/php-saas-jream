<?php

namespace Plugins\Internationalization;

class Base
{
    /**
     * @var string Default Language Class
     * eg: SetDefault('Es') to overwride
     */
    protected $default = 'En';

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * Overwrite the default System Language
     *
     * @param string Language to use
     *
     * @return mixed
     */
    protected static function setDefault($lang = 'En')
    {
        if (self::classLoader($lang)) {
            self::$defualt = $lang;
        }

        return false;
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * @return string Default language set
     */
    protected static function getDefault()
    {
        return self::$default;
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * Gets all the phrases from child class which overwrite these
     *
     * @return array
     */
    protected static function getPhrases(): array
    {
        return self::$phrases;
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * @param string $lang
     * @param bool   $return
     *
     * @return bool|string
     */
    protected static function classLoader(string $lang, $return = false)
    {
        $lang  = ucwords(strtolower($lang));
        $class = "\\Plugins\\Internationalization\\$lang";

        if (class_exists($class)) {
            // Return the class
            if ($return) {
                return $class;
            }

            //  Not Found
            return true;
        }

        return false;
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * Phrases, Generally we want to replace phrases rather than words since languages
     * won't properly handle a word-for-word translation.
     *
     * Always update this file first
     *
     * @var array All Default Languages in the Base File
     */
    protected static $phrases = [
        'name'    => null,
        'age'     => null,
        'profile' => null,
        'alias'   => null,
    ];

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * Validate all the language files are written without nulls.
     *
     * @param $lang The language to use, eg: En, Es
     *
     * @throws \InvalidArgumentException
     *
     * @return array
     *
     */
    public static function validate($lang): array
    {
        $class = self::classLoader($lang, true);
        if (!$class) {
            throw new \InvalidArgumentException(
                "The language class: $lang, does not exist, please check the Internationalization Folder."
            );
        }

        // Get the overwritten phrases from the subclass.
        $phrases = $class::getPhrases();
        $missing = [];
        foreach ($phrases as $key => $value) {
            if ($value === null) {
                $missing[] = $key;
            }
        }

        // Array of missing definitions
        return $missing;
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
}

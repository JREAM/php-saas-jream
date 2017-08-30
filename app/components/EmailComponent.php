<?php

use Phalcon\Mvc\User\Component;

/**
 * Email Renderer
 *
 * Phalcon\Mvc\User\Component extends abstract class Phalcon\Di\Injectable
 */
class EmailComponent extends Component
{

    // -----------------------------------------------------------------------------

    /**
     * Creates an email based on templates
     *
     * @param  string $template     A file in the emails folder
     * @param  array  $replacements associative array of replacements
     *
     * @return string
     */
    public function create(string $template, array $replacements = []) : string
    {
        // The wrapper Template
        $tpl_base = $this->_getFile('base');

        // The inner template
        $tpl_inner = $this->_getFile($template);

        if (!$tpl_inner) {
            throw new \InvalidArgumentException("Email template not found: $template");
        }

        // Replace the variables with placeholders
        foreach ($replacements as $key => $value) {
            $tpl_inner = str_replace("{%$key%}", $value, $tpl_inner);
        }

        // Return the rendered content
        return str_replace('{%content%}', $tpl_inner, $tpl_base);
    }

    // -----------------------------------------------------------------------------

    /**
     * Fetches a file
     *
     * @param  string $template Email Template Name
     *
     * @return boolean|string
     */
    private function _getFile(string $template)
    {
        $config = $this->di->get('config');

        $filename = $config->get('emailsDir') . basename($template) . '.php';

        if (file_exists($filename) && is_readable($filename)) {
            return file_get_contents($filename);
        }

        return false;
    }

    // -----------------------------------------------------------------------------

}

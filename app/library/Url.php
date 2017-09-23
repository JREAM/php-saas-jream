<?php

namespace Library;

class Url
{

    /**
     * Get the HTTP_HOST (Does not include http(s) info)
     *
     * @return string
     */
    public static function getHost() : string
    {
        // Prevent Header Injection Possibility
        return htmlspecialchars($_SERVER['HTTP_HOST'], ENT_QUOTES);
    }

    /**
     * Tells whether HTTP or HTTPS with a global function
     *
     * @return string  returns 'http' or 'https'
     */
    public static function getHttpMode() : string
    {
        if ( !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
            return 'https';
        }

        return 'http';
    }

    /**
     * Gets the base URL
     *
     * @param  string $append Add to URL (Don't include a starting /)
     *
     * @return string URL without trailing slash; http[s]://domain.tld[/append/url]
     */
    public static function get($append = false) : string
    {
        // Strip away the http(s):// (If it exists)
        $site_url = preg_replace('/(^https?)+(:\/{2})/i', '', self::getHost());

        // Produces: http(s)://site.tld (no trailing slash)
        $url = sprintf("%s://%s", self::getHttpMode(), rtrim($site_url, '/'));

        // If set, append a local URI to the main TLD without trailing slash.
        if ($append && is_string($append)) {
            $url .= '/' . trim($append, "/");
        }

        // Sanitize the URL!
        return filter_var($url, FILTER_SANITIZE_URL);
    }

    /**
     * Alias for get()
     *
     * @param bool $append
     *
     * @return string
     */
    public static function getAbsolute($append = false) : string
    {
        return self::get($append);
    }


    /**
     * @return string Current URL without trailing slash
     */
    public static function getCurrent() : string
    {
        $uri = trim($_SERVER['REQUEST_URI'], '/');
        $url = sprintf("%s/%s", self::get(), $uri);

        return filter_var($url, FILTER_SANITIZE_URL);
    }
}

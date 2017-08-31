<?php

namespace Library;

use Phalcon\Mvc\User\Component;

/**
 * Class TokenManager
 *
 * @package Library
 */
class TokenManager extends Component
{
    protected $session_key = 'sessionToken';

    /**
     * TokenManager constructor.
     *
     * Starts a session if one does not exist, prevents errors.
     */
    public function __construct()
    {
        if (!$this->session->isStarted()) {
            $this->session->start();
        }
    }

    /**
     * Generates token per session
     *
     * @throws \Exception
     *
     * @return void
     */
    public function generate() : void
    {
        $this->session->set($this->session_key, [
            'tokenKey'   => $this->security->getTokenKey(),
            'token'      => $this->security->getToken(),
        ]);

        // If a Token was not created its a problem!
        if (!$this->session->has($this->session_key)) {
            throw new \Exception("Session was not created");
        }
    }

    public function regenerate() : void
    {
        $this->generate();
    }

    /**
     * Checks token given values against session values
     *
     * @param string $tokenKey Can be the full tokenKey+token (As with AJAX Post), or only one
     * @param string $token
     *
     * @return boolean
     */
    public function validate(string $tokenKey, string ...$token) : boolean
    {
        if (!$this->session->has($this->session_key)) {
            return false;
        }

        $sessionTokens = $this->session->get($this->session_key);

        // If the token has a pipe "|" we shall explode it into two parts.
        // This comes from the AJAX Header Call with one string parameter.
        if (strpos($tokenKey, '|') !== false) {
            $parts = explode('|', $tokenKey);
            $tokenKey = $parts[0];
            $token = $parts[1];
        } else {
            $token = $token[0];
        }

        if ($sessionTokens['tokenKey'] == $tokenKey && $sessionTokens['token'] == $token) {
            return true;
        }

        return false;
    }

    /**
     * Checks if user have token or not
     *
     * @return boolean
     */
    public function hasToken() : boolean
    {
        if ($this->session->has($this->session_key)) {
            return (boolean) true;
        }

        return (boolean) false;
    }

    /**
     * Gets token values from session
     *
     * @return array|string|bool
     */
    public function getTokens()
    {
        if ($this->session->has($this->session_key)) {
            $tokens = $this->session->get($this->session_key);

            return [
                'tokenKey'   => $tokens['tokenKey'],
                'token'      => $tokens['token'],
            ];
        }

        return false;
    }

}

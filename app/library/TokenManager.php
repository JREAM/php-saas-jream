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
    public function generate()
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

    public function regenerate()
    {
        $this->generate();
    }

    /**
     * Checks token given values against session values
     *
     * @param string $tokenKey
     * @param string $token
     *
     * @return bool
     */
    public function validate(string $tokenKey, string $token): bool
    {
        if (!$this->session->has($this->session_key)) {
            return false;
        }

        $tokens = $this->session->get($this->session_key);
        if ($tokens['tokenKey'] == $tokenKey && $tokens['token'] == $token) {
            return true;
        }

        return false;
    }

    /**
     * Checks if user have token or not
     *
     * @return bool
     */
    public function hasToken()
    {
        if ($this->session->has($this->session_key)) {
            return true;
        }

        return false;
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

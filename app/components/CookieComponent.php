<?php

use Phalcon\Mvc\User\Component;

/**
 * Cookie Assistant
 *
 * Phalcon\Mvc\User\Component extends abstract class Phalcon\Di\Injectable
 */
class CookieComponent extends Component
{

    /**
     * Set a Cookie (Global Encryption is based in config/settings.php
     *
     * @param $key   string
     * @param $value mixed
     */
    public function set(string $key, mixed $value, $time = false)
    {

        if (!$time) {
            $time = time() + (15 * 86400);
        }

        $this->cookies->set(
            $key,
            $value,
            $time
        );
    }

    // --------------------------------------------------------------

    /**
     * Get a Cookies Value
     *
     * @param $key string
     *
     * @return bool|mixed
     */
    public function get(string $key)
    {
        if (!$this->cookies->has($key)) {
            return false;
        }

        $item = $this->cookies->get($key);

        return $item->getValue();
    }

    // --------------------------------------------------------------

    /**
     * Delete a Cookie Key
     *
     * @param $key string
     *
     * @return bool
     */
    public function delete(string $key): bool
    {
        if (!$this->cookies->has($key)) {
            return false;
        }
        $item = $this->cookies->get($key);
        $item->delete();

        return true;
    }

    // --------------------------------------------------------------

    /**
     * Reset the users Cookies, This cannot return a Boolean as it returns an interface instead.
     */
    public function reset()
    {
        $this->cookies->reset();
    }

    // --------------------------------------------------------------

}

<?php
use Phalcon\Http\Response\Cookies;

/**
 * ==============================================================
 * Cookie Encryption is on by default,
 *          this just ensures it for my personal memory.
 * =============================================================
 */
$di->set('cookies', function () {
    $cookies = new Cookies();
    $cookies->useEncryption(true);

    return $cookies;
});

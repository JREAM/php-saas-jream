<?php
use Phalcon\Security;

/**
 * ==============================================================
 * Set the Security for Usage
 *
 * @important This comes before the Session
 * =============================================================
 */
$di->setShared('security', function () {
    $security = new Security();
    $security->setWorkFactor(12);

    return $security;
});

<?php

use Phalcon\Mvc\Router;

/**
 * ==============================================================
 * Main Router
 * =============================================================
 */
$router = new Router();
$router->removeExtraSlashes(true);
$router->setDefaultController('index');
$router->setDefaultAction('index');
$router->setDefaultNamespace('Controllers');

$router ->add('/lab',     ['action' => 'lab'])->setName('lab');
$router ->add('/terms',   ['action' => 'terms'])->setName('terms');
$router ->add('/updates', ['action' => 'updates'])->setName('updates');

/**
 * ==============================================================
 * Newsletter Routes
 * =============================================================
 */
$router->add('newsletter')->setName('newsletter');
$router->add('newsletter/subscribe')->setName('newsletter_subscribe');
$router->add('newsletter/verifysubscribe')->setName('newsletter_verify_subscribe');
$router->add('newsletter/unsubscribe')->setName('newsletter_unsubscribe');

/**
 * ==============================================================
 * Dashboard Routes
 * =============================================================
 */
$router->add('/dashboard/:controller/:action/:params', [
    'namespace'  => 'Controllers\Dashboard',
    'controller' => 1,
    'action'     => 2,
    'params'     => 3,
]);

$router->add('/dashboard/:controller', [
    'namespace'  => 'Controllers\Dashboard',
    'controller' => 1,
]);

$router->add('/dashboard', [
    'namespace'  => 'Controllers\Dashboard',
    'controller' => 'dashboard',
    ["GET",]
])
->setName('dashboard');

/**
 * ==============================================================
 * API Routes
 * =============================================================
 */

//$router->addPost
$router->add('/api/:controller/:action/:params', [
    'namespace'  => 'Controllers\Api',
    'controller' => 1,
    'action'     => 2,
    'params'     => 3,
]);

$router->add('/api/:controller', [
    'namespace'  => 'Controllers\Api',
    'controller' => 1,
]);

$router->add('/api', [
    'namespace'  => 'Controllers\Api',
    'controller' => 'api',
])
->setName('api');

/**
 * ==============================================================
 * User Routes
 * =============================================================
 */
$router->add('/logout', [
    'namespace' => 'Controllers\Api',
    'controller' => 'Auth',
    'action' => 'logout'
]);

$router->add('api/auth/logout')->setName('logout');
$router->add('api/auth/login')->setName('login');
$router->add('api/auth/register')->setName('register');
$router->add('api/auth/password')->setName('password');


/**
 * ==============================================================
 * Product Routes
 * =============================================================
 */
//$router->add('product/course/:params', [
//    'controller' => 'Product',
//    'action' => 'course',
//    'params' => 1,
//])->setName('course');

$router->add('product/course/preview/:params', [
    'controller' => 'Product',
    'action' => 'coursePreview',
    'params' => 1,
])->setName('course.preview');



/**
 * ==============================================================
 * Old Routes ___
 * =============================================================
 */
$router ->add('/learning', ['controller' => 'product'])
        ->setName('learning');

$router ->add('/forum', ['controller' => 'product'])
        ->setName('forum');


return $router;

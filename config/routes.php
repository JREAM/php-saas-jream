<?php

use Phalcon\Mvc\Router;

/**
 * ==============================================================
 * Main Router
 * =============================================================
 */
$router = new Router();
$router->removeExtraSlashes(true);
$router->setDefaults([
    'namespace'     => 'App\Controllers',
    'controller'    => 'index',
    'action'        => 'index'
]);


/**
 * ==============================================================
 * User Routes
 * =============================================================
 */
$router->add('user/login')->setName('login');
$router->add('user/logout')->setName('logout');
$router->add('user/register')->setName('register');
$router->add('user/password')->setName('password');

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
 * Index Based Routes
 * =============================================================
 */
$router ->add('/lab', [
    'controller' => 'index',
    'action'     => 'lab'
])->setName('lab');

$router ->add('/terms', [
    'controller' => 'index',
    'action'     => 'terms'
])->setName('terms');

$router ->add('/updates', [
    'controller' => 'index',
    'action'     => 'updates'
])->setName('updates');

/**
 * ==============================================================
 * Dashboard Routes
 * =============================================================
 */
$router->add('/dashboard/:controller/:action/:params', [
    'namespace'  => 'App\Controllers\Dashboard',
    'controller' => 1,
    'action'     => 2,
    'params'     => 3,
]);

$router->add('/dashboard/:controller', [
    'namespace'  => 'App\Controllers\Dashboard',
    'controller' => 1,
]);

$router->add('/dashboard', [
    'namespace'  => 'App\Controllers\Dashboard',
    'controller' => 'dashboard',
])
->setName('dashboard');

/**
 * ==============================================================
 * API Routes
 * =============================================================
 */

$router->add('/api/:controller/:action/:params', [
    'namespace'  => 'App\Controllers\Api',
    'controller' => 1,
    'action'     => 2,
    'params'     => 3,
]);

$router->add('/api/:controller', [
    'namespace'  => 'App\Controllers\Api',
    'controller' => 1,
]);

$router->add('/api', [
    'namespace'  => 'App\Controllers\Api',
    'controller' => 'api',
])
->setName('api');

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

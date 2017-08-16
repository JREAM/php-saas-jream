<?php

use Phalcon\Mvc\Router;
use Phalcon\Mvc\Router\Group;

/**
 * ==============================================================
 * Website Routes
 * =============================================================
 */
$router = new Router();
$router->removeExtraSlashes(true);
$router->setDefaultController('index');

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
    'namespace'  => 'Dashboard',
    'controller' => 1,
    'action'     => 2,
    'params'     => 3,
]);

$router->add('/dashboard/:controller', [
    'namespace'  => 'Dashboard',
    'controller' => 1,
]);

$router->add('/dashboard', [
    'namespace'  => 'Dashboard',
    'controller' => 'dashboard',
])
->setName('dashboard');


/**
 * ==============================================================
 * API Routes
 * =============================================================
 */
$router->add('/api/v1/:controller/:action/:params', [
    'namespace'  => 'Api',
    'controller' => 1,
    'action'     => 2,
    'params'     => 3,
]);

$router->add('/api/v1/:controller/', [
    'namespace'  => 'Api',
    'controller' => 1,
]);

/**
 * ==============================================================
 * Admin Routes
 * =============================================================
 */
$router->add('/admin/:controller/:action/:params', [
    'namespace'  => 'Admin',
    'controller' => 1,
    'action'     => 2,
    'params'     => 3,
]);

$router->add('/admin/:controller', [
    'namespace'  => 'Admin',
    'controller' => 1,
]);

$router->add('/admin', [
    'namespace'  => 'Admin',
    'controller' => 'admin',
])
->setName('admin');


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

// End of File
// --------------------------------------------------------------

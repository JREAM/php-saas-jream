<?php

// --------------------------------------------------------------
// Site Routes (Custom)
// --------------------------------------------------------------

$router = new Phalcon\Mvc\Router();
$router->removeExtraSlashes(true);
$router->setDefaultController('index');

// --------------------------------------------------------------
// User Routes
// --------------------------------------------------------------
$router->add('user/login')->setName('login');
$router->add('user/logout')->setName('logout');
$router->add('user/register')->setName('register');
$router->add('user/password')->setName('password');

// --------------------------------------------------------------
// Old Overwrites
// --------------------------------------------------------------
$router ->add('/learning', ['controller' => 'product'])
        ->setName('learning');

$router ->add('/forum', ['controller' => 'product'])
        ->setName('forum');

$router ->add('/blog', ['controller' => 'product'])
        ->setName('blog');

// --------------------------------------------------------------
// Index Based Routes
// --------------------------------------------------------------
$router ->add('/lab', ['controller' => 'index', 'action' => 'lab'])
        ->setName('lab');

$router ->add('/terms', ['controller' => 'index', 'action' => 'terms'])
        ->setName('terms');

$router ->add('/updates', ['controller' => 'index', 'action' => 'updates'])
        ->setName('updates');

// --------------------------------------------------------------
// Services SubRoutes
// --------------------------------------------------------------
$router->add('/services/:controller/:action/:params', [
    'namespace'  => 'Services',
    'controller' => 1,
    'action'     => 2,
    'params'     => 3,
]);

$router->add('/services/:controller', [
    'namespace'  => 'Services',
    'controller' => 1,
]);

$router->add('/services', [
    'namespace'  => 'Services',
    'controller' => 'services',
])
->setName('services');

// --------------------------------------------------------------
// ThirdParty SubRoutes
// --------------------------------------------------------------
$router->add('/third-party/:controller/:action/:params', [
    'namespace'  => 'ThirdParty',
    'controller' => 1,
    'action'     => 2,
    'params'     => 3,
]);

$router->add('/third-party/:controller', [
    'namespace'  => 'Party',
    'controller' => 1,
]);

$router->add('/third-party', [
    'namespace'  => 'Party',
    'controller' => 'services',
])
->setName('third-party');

// --------------------------------------------------------------
// Dashboard SubRoutes
// --------------------------------------------------------------
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

// --------------------------------------------------------------
// Dashboard SubRoutes
// --------------------------------------------------------------
$router->add('/hire/:controller/:action/:params', [
    'namespace'  => 'Hire',
    'controller' => 1,
    'action'     => 2,
    'params'     => 3,
]);

$router->add('/hire/:controller', [
    'namespace'  => 'Hire',
    'controller' => 1,
]);

$router->add('/hire', [
    'namespace'  => 'Hire',
    'controller' => 'hire',
])
->setName('hire');


// --------------------------------------------------------------
// Admin SubRoutes
// --------------------------------------------------------------
$router->add('/admin/:controller/:action/:params', [
    'namespace'  => 'Admin',
    'controller' => 1,
    'action'     => 2,
    'params'     => 3,
]);

$router->add('/admin/:controller/:action', [
    'namespace'  => 'Admin',
    'controller' => 1,
    'action'     => 2
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

return $router;

// End of File
// --------------------------------------------------------------

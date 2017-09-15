<?php

use Phalcon\Acl;
use Phalcon\Acl\Adapter\Memory as AclList;
use Phalcon\Acl\Resource;
use Phalcon\Acl\Role;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\User\Plugin;

/**
 * Permission
 *
 * Prevents User Types from accessing areas they are not allowed in.
 */
class PermissionPlugin extends Plugin
{

    // ----------------------------------------------------------------------------

    /**
     * @param const String to check for ACL a user having a role set
    */
    const ACL_SESSION_ID  = 'role';

    /**
     * @param const Non Ajax Calls are redirected to the Login
    */
    const REDIRECT_DENIED = '/user/login';

    /**
     * @param const User Roles  These come from the Database (users)
    */
    const GUEST = 'guest';
    const USER  = 'user';
    const ADMIN = 'admin';
    const BOT   = 'bot'; // (For Testing Acceptance/Functional)

    // ----------------------------------------------------------------------------

    /**
     * @var resource Path to the ACL file to save/read for Permissions
     */
    protected $saveAclFile;

    /**
     * Accessible to everyone
     *
     * @var array
     */
    protected $publicResources = [
        // API Controllers (Dynamically Added), eg:
        // 'Controllers\Api:*'        => ['*'],

        // Formal Controllers
        'Controllers:contact'    => ['*'],
        'Controllers:index'      => ['*'],
        'Controllers:newsletter' => ['*'],
        'Controllers:product'    => ['*'],
        'Controllers:promotion'  => ['*'],
        'Controllers:test'       => ['*'],
        'Controllers:user'       => ['*'],
        'Controllers:checkout'   => ['*'],
    ];

    /**
     * Accessible to Users (and Admins)
     *
     * @var array
     */
    protected $userResources = [
        'Controllers\Dashboard:account'      => ['*'],
        'Controllers\Dashboard:course'       => ['*'],
        'Controllers\Dashboard:dashboard'    => ['*'],
        'Controllers\Dashboard:notification' => ['*'],
        'Controllers\Dashboard:support'      => ['*'],
        'Controllers\Dashboard:question'     => ['*'],
        'Controllers\Dashboard:youtube'      => ['*'],
    ];

    /**
     * Accessible to Admins
     *
     * @var array
     */
    protected $adminResources = [
        'Controllers\Admin:admin' => ['*'],
    ];

    // ----------------------------------------------------------------------------

    public function initialize()
    {
        $config = $this->di->get('config');
        $this->saveAclFile = $config->get('securityDir') . 'acl.data';
    }

    // ----------------------------------------------------------------------------

    /**
     * Triggers before a route is dispatched
     *
     * @param  Event      $event
     * @param  Dispatcher $dispatcher
     *
     * @return null|string
     */
    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher) : ?string
    {

//        echo '<pre>';
//        var_dump($this->persistent->acl);
//die;
        // Debug:
        // $this->session->destroy();

        // Get the current role, If none is set they are a Guest.
        $currentRole = $this->session->get(self::ACL_SESSION_ID);
        if (!$currentRole) {
            $currentRole = self::GUEST;
        }

        // Get the current Namespace/Controller/Action from the Dispatcher
        $namespace  = $dispatcher->getNamespaceName();
        $controller = $dispatcher->getControllerName();
        $action     = $dispatcher->getActionName();

        // Get the ACL Rule List
        $acl = $this->_getACL();

        // (To Debug, Go to the bottom of the file and copy/paste the commented out code)


        // See if they have permission
        // @important Notice we are checking the namespace!
        if ($acl->isAllowed($currentRole, "$namespace:$controller", $action) != Acl::ALLOW)
        {

            if ($this->request->isAjax()) {
                return (new \Library\Output(0, 'Permission Denied for this area (ACL)'))->send();
            }

            $this->flash->error("Permission Denied for this area (ACL).");
            // return $this->response->redirect(self::REDIRECT_DENIED);
        }

        return null;
    }

    // ----------------------------------------------------------------------------

    /**
     * Build the Session ACL list one time if it's not set
     *
     * @return \Phalcon\Acl\Adapter\Memory  Persistent Session Data
     */
    protected function _getACL() : \Phalcon\Acl\Adapter\Memory
    {
        if (isset($this->persistent->acl)) {
            return $this->persistent->acl;
        }

        if (!isset($this->persistent->acl))
        {
            $acl = new AclList();
            $acl->setDefaultAction(Acl::DENY);

            $roles = [
                self::GUEST => new Role(self::GUEST),
                self::USER  => new Role(self::USER),
                self::ADMIN => new Role(self::ADMIN),
                self::BOT   => new Role(self::BOT),
            ];

            // Sets the Api Controllers to Public Resources, Only run
            // when ACL is not persistently set.
            // @important (! ! !) Site will not work without this (! ! !)
            $this->setApiControllers();

            // @TODO
            // $this->setPermissionsFromDirectory('api', 'publicResources');
            // $this->setPermissionsFromDirectory('dashboard', 'userResources');

            // Place all the roles inside the ACL Object
            foreach ($roles as $role) {
                $acl->addRole($role);
            }

            // Public Resources
            foreach ($this->publicResources as $resource => $action) {
                $acl->addResource(new Resource($resource), $action);
            }

            // User Resources
            foreach ($this->userResources as $resource => $action) {
                $acl->addResource(new Resource($resource), $action);
            }

            // Admin Resources
            foreach ($this->adminResources as $resource => $action) {
                $acl->addResource(new Resource($resource), $action);
            }

            // Allow ALL Roles to access the Public Resources
            foreach ($roles as $role) {
                foreach ($this->publicResources as $resource => $action) {
                    $acl->allow($role->getName(), $resource, '*');
                }
            }

            // Allow User/Admin/Bot to access the User Resources
            foreach ($this->userResources as $resource => $actions) {
                foreach ($actions as $action) {
                    $acl->allow(self::USER, $resource, $action);
                    $acl->allow(self::ADMIN, $resource, $action);
                    $acl->allow(self::BOT, $resource, $action);
                }
            }

            // Allow Admin to access the Admin Resources
            foreach ($this->adminResources as $resource => $actions) {
                foreach ($actions as $action) {
                    $acl->allow(self::ADMIN, $resource, $action);
                }
            }

            return $acl;
        }

    }

    // -----------------------------------------------------------------------------

    /**
     * Loads all API Controllers as Public Resources.
     * They are self-protected so this file need not be updated so often.
     *
     * @return void
     */
    protected function setApiControllers() : void
    {
        $di = \Phalcon\Di::getDefault();
        $config = $di->get('config');

        $dir = new \DirectoryIterator( $config->controllersDir . 'api/');

        // Iterate the API Controllers
        foreach ($dir as $file)
        {
            if ($file->isDot() == false && $file->isFile())
            {
                // Filename only without extension, case insensitive replace
                $info = pathinfo($file->getBaseName());
                $file = strtolower(str_ireplace('controller', '', $info['filename']));

                // Turn it into Controllers\Api\Auth => [*]
                $namespacedController = sprintf('Controllers\Api:%s', $file);

                // Append to Permissions
                $this->publicResources[$namespacedController] = ['*'];
            }
        }
    }

    /**
     * Sets all permissions for controllers within a namespace that must match the directory (PSR)
     *
     * @param str $namespace       This must match the folder name and namespace under /controllers
     * @param str $applyToResource This must be one the the <public|user|admin>Resources
     *
     * @return void
     */
    protected function setPermissionsFromDirectory($namespace, $applyToResource) : void
    {
        $validResources = ['publicResources', 'userResources', 'adminResources'];

        if (!in_array($applyToResource, $validResources)) {
            throw new \InvalidArgumentException("
                You setPermissionsFrom ($applyToResource) and they must be one of: " .
                explode(',', $validResources)
            );
        }

        $di = \Phalcon\Di::getDefault();
        $config = $di->get('config');

        $dir = new \DirectoryIterator( $config->controllersDir . strtolower($namespace) . '/');

        // Iterate the API Controllers
        foreach ($dir as $file)
        {
            if ($file->isDot() == false && $file->isFile())
            {
                // Filename only without extension, case insensitive replace
                $info = pathinfo($file->getBaseName());
                $file = strtolower(str_ireplace('controller', '', $info['filename']));

                // Turn it into Controllers\Api\Auth => [*]
                $namespacedController = sprintf('Controllers\%s:%s', $namespace, $file);

                PC::Debug($namespacedController);
                // Append to Permissions
                $this->{$applyToResource}[$namespacedController] = ['*'];
            }
        }

        // The class property, eg: $this->publicResources.
        // $this->{$validResources}
    }


    // ----------------------------------------------------------------------------

    // For Debugging ACL Permissions with PHP Console + Chome Extension
    // \PC::debug([
    //     'currentRole' => $currentRole,
    //     'Namespace_Controller_Action' => sprintf("%s\%s::%s", $namespace, $controller, $action),
    //     'IsResource' => $acl->isResource($namespace . ":" . $controller),
    //     'action' => $action,
    //     'isAllowed' => $acl->isAllowed($currentRole, $namespace . ":" . $controller, $action),
    //     'Acl::ALLOW Code' => Acl::ALLOW,
    // ]);

    // ----------------------------------------------------------------------------
    //
}

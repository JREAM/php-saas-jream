<?php

namespace Controllers\Dashboard;

use \Phalcon\Tag;
use Controllers\BaseController;

class AccountController extends BaseController
{
    const REDIRECT_SUCCESS = "dashboard/account";
    const REDIRECT_FAILURE = "dashboard/account";
    const REDIRECT_DELETE = "dashboard/account/delete";
    const REDIRECT_LOGOUT = "user/logout";

    /**
     * @return void
     */
    public function onConstruct()
    {
        parent::initialize();
        Tag::setTitle('Account | ' . $this->di['config']['title']);
    }

    /**
     * @return void
     */
    public function indexAction()
    {
        $this->view->setVars([
            'changeEmailForm'    => new \Forms\ChangeEmailForm(),
            'changePasswordForm' => new \Forms\ChangePasswordForm(),
            'user'               => \User::findFirstById($this->session->get('id')),
            'purchases'          => \UserPurchase::findByUserId($this->session->get('id')),
            'timezones'          => \DateTimeZone::listIdentifiers(),
        ]);

        $this->view->pick("dashboard/account");
    }

    /**
     * @return void
     */
    public function deleteAction()
    {
        $this->view->setVars([
            'user'     => \User::findFirstById($this->session->get('id')),
        ]);

        $this->view->pick("dashboard/account-delete");
    }

}

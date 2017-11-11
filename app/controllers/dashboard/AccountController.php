<?php

namespace Controllers\Dashboard;

use Phalcon\Tag;
use Phalcon\Mvc\View;
use Phalcon\Http\Response;
use Controllers\BaseController;

class AccountController extends BaseController
{

    /**
     * @return void
     */
    public function onConstruct(): void
    {
        parent::initialize();
        Tag::setTitle('Account | ' . $this->di[ 'config' ][ 'title' ]);
    }

    // -----------------------------------------------------------------------------

    /**
     * @return View
     */
    public function indexAction(): View
    {
        $this->view->setVars([
            'changeEmailForm'    => new \Forms\ChangeEmailForm(),
            'changePasswordForm' => new \Forms\ChangePasswordForm(),
            'changeAliasForm'    => new \Forms\ChangeAliasForm(),
            'user'               => \User::findFirstById($this->session->get('id')),
            'purchases'          => \UserPurchase::findByUserId($this->session->get('id')),
            'timezones'          => \DateTimeZone::listIdentifiers(),
        ]);

        return $this->view->pick("dashboard/account");
    }

    // -----------------------------------------------------------------------------

    /**
     * @return View
     */
    public function deleteAction(): View
    {
        $this->view->setVars([
            'user' => \User::findFirstById($this->session->get('id')),
        ]);

        return $this->view->pick("dashboard/account-delete");
    }
}

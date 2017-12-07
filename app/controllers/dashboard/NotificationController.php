<?php

namespace Controllers\Dashboard;

use Phalcon\Tag;
use Phalcon\Mvc\View;
use Phalcon\Http\Response;
use Controllers\BaseController;

class NotificationController extends BaseController
{
    /**
     * @return void
     */
    public function onConstruct(): void
    {
        parent::initialize();
        Tag::setTitle('Notifications | ' . $this->di[ 'config' ][ 'title' ]);
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * @return View
     */
    public function indexAction(): View
    {
        $notifications = \UserNotification::findByUserId($this->session->get('id'));

        $this->view->setVars([
            'notifications' => $notifications,
        ]);

        return $this->view->pick("dashboard/notification");
    }
}

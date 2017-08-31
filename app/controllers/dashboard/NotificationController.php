<?php

namespace Controllers\Dashboard;

use \Phalcon\Tag;
use Controllers\BaseController;

class NotificationController extends BaseController
{
    /**
     * @return void
     */
    public function onConstruct() : void
    {
        parent::initialize();
        Tag::setTitle('Notifications | ' . $this->di['config']['title']);
    }

    /**
     * @return void
     */
    public function indexAction() : void
    {
        $notifications = \UserNotification::findByUserId($this->session->get('id'));

        $this->view->setVars([
            'notifications' => $notifications,
        ]);

        $this->view->pick("dashboard/notification");
    }

}

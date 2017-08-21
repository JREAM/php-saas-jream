<?php

namespace Api;

use \User;

/**
 * @RoutePrefix("/api/user")
 */
class UserController extends ApiController
{

    /**
     * @return void
     */
    public function onConstruct()
    {
        parent::initialize();
    }

    // --------------------------------------------------------------

    /**
     * @return string JSON
     */
    public function updateTimezoneAction()
    {
        $user_id = $this->session->get('user_id');
    }

    /**
     * @return string JSON
     */
    public function updateEmailAction()
    {
        $user_id = $this->session->get('user_id');
    }

    /**
     * @return string JSON
     */
    public function updateNotificationsAction()
    {
        $user_id = $this->session->get('user_id');
    }

}

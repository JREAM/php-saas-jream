<?php
declare(strict_types=1);

namespace Controllers\Api;

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

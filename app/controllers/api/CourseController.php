<?php

namespace Api;

use \User;

/**
 * @RoutePrefix("/api/course")
 */
class CourseController extends ApiController
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
     * @Route(
     *     "/api/course/update-progress/{id:[0-9]+}",
     *     methods="POST",
     * )
     *
     * @return string JSON
     */
    public function updateProgressAction()
    {
        $user_id = $this->session->get('user_id');

    }

}

<?php

namespace App\Controllers\Api;

use App\Models\UserAction;

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

        $productCourseId = (int) $this->request->getPost('contentId');
        $productId = (int) $this->request->getPost('productId');
        $action = $this->request->getPost('action');
        $value = (int) $this->request->getPost('value');

        $userAction = new UserAction();
        $userAction = $userAction->getAction(
            $action,
            $user_id,
            $productCourseId
        );

        if ($userAction) {
            $userAction->value = (int)$value;
            $userAction->save();
            $this->output(1, ['value' => $value]);
        }

        $userAction = new UserAction();
        $userAction->action = $action;
        $userAction->user_id = $user_id;
        $userAction->product_id = $productId;
        $userAction->product_course_id = $productCourseId;
        $userAction->value = $value;
        $userAction->save();

        if ($userAction->getMessages() == false) {
            return $this->output(1, ['value' => $value]);
        }

        return $this->output(0, $userAction->getMessagesString());
    }

}

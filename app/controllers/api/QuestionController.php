<?php

namespace Api;

use \User;

/**
 * @RoutePrefix("/api/question")
 */
class QuestionController extends ApiController
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
    public function addQuestionAction()
    {
        $user_id = $this->session->get('user_id');
    }

    /**
     * @return string JSON
     */
    public function addReplyAction()
    {
        $user_id = $this->session->get('user_id');
    }

    /**
     * @return string JSON
     */
    public function updateAction()
    {
        $user_id = $this->session->get('user_id');
    }

    /**
     * @return string JSON
     */
    public function deleteAction()
    {
        $user_id = $this->session->get('user_id');
    }

}

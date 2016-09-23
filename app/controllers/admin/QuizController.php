<?php
namespace Admin;
use \Phalcon\Tag;

class QuizController extends \BaseController
{
    const REDIRECT_FAILURE = 'quiz';
    const REDIRECT_SUCCESS = 'quiz';

    // --------------------------------------------------------------

    /**
     * @return void
     */
    public function onConstruct()
    {
        if (!$this->session->has('id') || $this->session->get('role') != 'admin') {
            $this->redirect('index');
        }

        parent::initialize();
        Tag::setTitle('Admin');
    }

    // --------------------------------------------------------------

    public function indexAction()
    {
        $this->view->setVars([
            'quizzes' => \Quiz::find()
        ]);
        $this->view->pick('admin/quiz');
    }

    // --------------------------------------------------------------

    public function editAction($id = false) {
        if ($id == true) {
            // Get the quiz STUFF here
        }
        $this->view->pick('admin/quiz-edit');
    }

    // --------------------------------------------------------------

    public function doCreateAction()
    {
        $quiz = new \Quiz();
        $quiz->title = $this->input->getPost('title');
        $quiz->description = $this->input->getPost('description');
        $quiz->save();
    }

    // --------------------------------------------------------------

    public function doEditAction($quizId)
    {
        $quiz = Quiz::findFirstById($quizId);
    }

    // --------------------------------------------------------------

}

// End of File
// --------------------------------------------------------------
<?php
namespace Admin;
use \Phalcon\Tag;

class LiveController extends \BaseController
{
    const REDIRECT_SUCCESS = 'live';
    const REDIRECT_FAILURE = 'live';

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
        $this->view->pick('admin/live');
    }

    // --------------------------------------------------------------

    public function createAction()
    {

        $user = \User::find();

        $this->view->setVars([
            'user' => [
                'total' => count($user)
            ],
            'form' => new \AdminEmailForm(),
            // CSRF
            'tokenKey' => $this->security->getTokenKey(),
            'token' => $this->security->getToken()
        ]);
        $this->view->pick('admin/live_create');
    }

    // --------------------------------------------------------------

    public function doCreateAction()
    {

    }

    // --------------------------------------------------------------

}

// End of File
// --------------------------------------------------------------
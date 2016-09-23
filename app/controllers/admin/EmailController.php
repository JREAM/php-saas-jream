<?php
namespace Admin;
use \Phalcon\Tag;

class EmailController extends \BaseController
{
    const REDIRECT_SUCCESS = 'email';
    const REDIRECT_FAILURE = 'email';

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
            'emails_unsent' => \MarketingEmail::findByHasSent(false),
            'emails_sent' => \MarketingEmail::findByHasSent(true)
        ]);
        $this->view->pick('admin/email');
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
        $this->view->pick('admin/email_create');
    }

    // --------------------------------------------------------------

    public function doCreateAction()
    {
        $subject = $this->request->getPost('subject');
        $content = $this->request->getPost('content');
        $purchase_status = $this->request->getPost('purchase_status');
        $login_status = $this->request->getPost('login_status');

        $marketing = new \MarketingEmail();
        $marketing->subject         = $subject;
        $marketing->content         = $content;
        $marketing->purchase_status = $purchase_status;
        $marketing->login_status    = $login_status;
        $marketing->has_sent        = 0;
        $result = $marketing->save();

        if ($result) {
            $this->output(1, ['id' => $marketing->id]);
            return true;
        }

        $this->output(0, $marketing->getMessagesList());
    }

    // --------------------------------------------------------------

    public function previewAction($emailId)
    {
        $this->view->setVars([
            'email' => \MarketingEmail::findFirstById($emailId)
        ]);
        $this->view->pick('admin/email_preview');
    }

    // --------------------------------------------------------------

    /**
     * Send a boatload of emails to the people in the database
     *
     * @return void
     */
    public function doSendAction()
    {
        $users = \User::find();
        foreach ($users as $user) {
            echo $user->id .' : ';
            echo $user->getEmail($user->id);
            echo '<br />';
        }
        // $users = $users->toArray();
        die;

        // Email shit

        if ($result) {
            $this->flash->success('Emails sent..');
        } else {
            $this->flash->error('There was a problem sending emails.');
        }
    }
}

// End of File
// --------------------------------------------------------------
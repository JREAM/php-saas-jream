<?php

namespace Controllers\Dashboard;

use \Phalcon\Tag;
use Controllers\BaseController;

/**
 * @RoutePrefix("/dashboard/support")
 */
class SupportController extends BaseController
{

    const REDIRECT_SUCCESS = 'dashboard';
    const REDIRECT_FAILURE = 'dashboard/support';

    private $_types = [
        'error'   => 'Report an Error',
        'support' => 'General Support',
    ];

    // --------------------------------------------------------------

    /**
     * @return void
     */
    public function onConstruct()
    {
        parent::initialize();
        Tag::setTitle('Support | ' . $this->di['config']['title']);
    }

    // --------------------------------------------------------------

    /**
     * @return void
     */
    public function indexAction()
    {
        $this->view->setVars([
            // Make sure the type is a forced
            'types'    => $this->_types,

            // CSRF
            'tokenKey' => $this->security->getTokenKey(),
            'token'    => $this->security->getToken(),
        ]);

        $this->view->pick("dashboard/support");
    }

    // --------------------------------------------------------------

    /**
     * @return void
     */
    public function doAction()
    {
        $this->component->helper->csrf(self::REDIRECT_FAILURE);

        $type = $this->request->getPost('type');
        $title = $this->request->getPost('title');
        $content = $this->request->getPost('content');

        if (!isset($this->_types[$type])) {
            $this->flash->error('Invalid Type.');

            return $this->redirect(self::REDIRECT_FAILURE);
        }

        $user = \User::findFirstById($this->session->get('id'));

        $userSupport = new \UserSupport();
        $userSupport->user_id = $user->id;
        $userSupport->type = $type;
        $userSupport->title = $title;
        $userSupport->content = $content;
        $result = $userSupport->save();

        if (!$result) {
            $this->flash->error($userSupport->getMessagesList());

            return $this->redirect(self::REDIRECT_FAILURE);
        }

        $content = $this->component->email->create('support', [
            'type'    => $type,
            'title'   => $title,
            'content' => $content,
            'email'   => $user->getEmail(),
            'alias'   => $user->getAlias(),
        ]);

        $mail_result = $this->di->get('email', [
            [
                'to_name'    => 'JREAM',
                'to_email'   => $this->config->email->to_question_address,
                'from_name'  => $user->getAlias(),
                'from_email' => $user->getEmail(),
                'subject'    => 'JREAM Support',
                'content'    => $content,
            ],
        ]);

        if (!in_array($mail_result->statusCode(), [200, 201, 202])) {
            $this->flash->error('There was a problem sending the email. We have logged the error!');
        } else {
            $this->flash->success('Your message has been submitted.');
        }

        return $this->redirect(self::REDIRECT_SUCCESS);
    }

    // --------------------------------------------------------------
}

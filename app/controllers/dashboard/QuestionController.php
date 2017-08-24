<?php

namespace Controllers\Dashboard;

use \Phalcon\Tag;
use Controllers\BaseController;

/**
 * @RoutePrefix("/dashboard/question")
 */
class QuestionController extends BaseController
{

    const REDIRECT_SUCCESS = 'dashboard/question/index/';
    const REDIRECT_FAILURE = 'dashboard/question/index/';
    const REDIRECT_FAILURE_PERMISSION = 'dashboard/';

    // --------------------------------------------------------------

    /**
     * @return void
     */
    public function onConstruct()
    {
        parent::initialize();
        Tag::setTitle('Questions | ' . $this->di['config']['title']);
    }

    // --------------------------------------------------------------

    /**
     * @param integer $productId
     *
     * @return void
     */
    public function indexAction($productId = false)
    {
        $product = \Product::findFirstById($productId);

        if (!$productId || $product->hasPurchased() == false) {
            $this->flash->error('There is no record of your purchase for this item.');

            return $this->redirect(self::REDIRECT_FAILURE_PERMISSION);
        }

        $this->view->setVars([
            'product'  => $product,
            'threads'  => \ProductThread::find([
                'product_id' => $productId,
                'order'      => 'id DESC',
            ]),
        ]);

        $this->view->pick('dashboard/question');
    }

    // --------------------------------------------------------------

    /**
     * @param integer $productId
     *
     * @return void
     */
    public function doAction($productId)
    {
        $this->view->disable();
        $this->component->helper->csrf(self::REDIRECT_FAILURE . $productId);

        $product = \Product::findFirstById($productId);

        if (!$productId || $product->hasPurchased() == false) {
            $this->output(0, 'You do not have permission to access this area.');

            return;
        }

        $title = $this->request->getPost('title');
        $content = $this->request->getPost('content');

        $thread = new \ProductThread();
        $thread->user_id = $this->session->get('id');
        $thread->product_id = $productId;
        $thread->title = $title;
        $thread->content = $content;
        $result = $thread->save();

        if (!$result) {
            $this->output(0, $thread->getMessagesList());

            return;
        }

        $url = getBaseUrl('dashboard/question/index/' . $productId . '#thread-id-' . $thread->id);

        $product = \Product::findFirstById($productId);
        $content = $this->component->email->create('question-thread', [
            'title'         => $title,
            'content'       => $content,
            'product_title' => $product->title,
            'url'           => $url,
        ]);

        // Parse any markdown code to HTML
        $parsedown = new \Parsedown();
        $content = $parsedown->parse($content);

        $mail_result = $this->di->get('email', [
            [
                'to_name'    => 'JREAM',
                'to_email'   => $this->config->email->to_question_address,
                'from_name'  => $this->config->email->from_name,
                'from_email' => $this->config->email->from_address,
                'subject'    => "JREAM New Question ({$product->title})",
                'content'    => $content,
            ],
        ]);

        $this->flash->success('Your question has been added.');

        formDataClear();

        $this->output(1, ['redirect' => getBaseUrl(self::REDIRECT_SUCCESS . $productId)]);
        return true;
    }

    // --------------------------------------------------------------

    /**
     * Reply Action
     *
     * @param  integer $productId
     * @param  integer $threadId
     *
     * @return void
     */
    public function doReplyAction($productId, $threadId)
    {
        $this->component->helper->csrf(self::REDIRECT_FAILURE . $productId);

        $product = \Product::findFirstById($productId);

        if (!$productId || $product->hasPurchased() == false) {
            $this->flash->error('There is no record of your purchase for this item.');

            return $this->redirect(self::REDIRECT_FAILURE_PERMISSION);
        }

        $content = $this->request->getPost('content');

        $thread = new \ProductThreadReply();
        $thread->user_id = $this->session->get('id');
        $thread->product_thread_id = $threadId;
        $thread->content = $content;
        $result = $thread->save();

        if (!$result) {
            $this->flash->error($thread->getMessagesList());

            return $this->redirect(self::REDIRECT_FAILURE . $productId);
        }

        $url = getBaseUrl('dashboard/question/index/' . $productId . '#thread-id-' . $threadId);

        $product = \Product::findFirstById($productId);
        $content = $this->component->email->create('question-thread-reply', [
            'content'       => $content,
            'product_title' => $product->title,
            'url'           => $url,
        ]);

        $mail_result = $this->di->get('email', [
            [
                'to_name'    => 'JREAM',
                'to_email'   => $this->config->email->to_question_address,
                'from_name'  => $this->config->email->from_name,
                'from_email' => $this->config->email->from_address,
                'subject'    => "JREAM Question Reply ({$product->title})",
                'content'    => $content,
            ],
        ]);

        $this->flash->success('Your reply has been added.');

        return $this->redirect(self::REDIRECT_SUCCESS . $productId);
    }

    // --------------------------------------------------------------
}

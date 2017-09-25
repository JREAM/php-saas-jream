<?php

declare(strict_types=1);

namespace Controllers\Api;

use Phalcon\Http\Response;
use Product;

class QuestionController extends ApiController
{

    public function onConstruct()
    {
        parent::initialize();
    }

    // -----------------------------------------------------------------------------

    /**
     * @param int $productId
     *
     * @return Response
     */
    public function createAction(int $productId): Response
    {
        $product = \Product::findFirstById($productId);

        if ( ! $productId || $product->hasPurchased() == false) {
            return $this->output(0, 'You do not have permission to access this area.');
        }

        $title   = $this->request->getPost('title');
        $content = $this->request->getPost('content');

        $thread             = new \ProductThread();
        $thread->user_id    = $this->session->get('id');
        $thread->product_id = $productId;
        $thread->title      = $title;
        $thread->content    = $content;
        $result             = $thread->save();

        if ( ! $result) {
            return $this->output(0, $thread->getMessagesList());
        }

        $url = \Library\Url::get('dashboard/question/index/' . $productId . '#thread-id-' . $thread->id);

        $product = \Product::findFirstById($productId);
        $content = $this->component->email->create('question-thread', [
            'title'         => $title,
            'content'       => $content,
            'product_title' => $product->title,
            'url'           => $url,
        ]);

        // Parse any markdown code to HTML
        $parsedown = new \Parsedown();
        $content   = $parsedown->parse($content);

        // Send an email
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

        return $this->output(1, [
            'msg'      => 'Your question has been added',
            'redirect' => \Library\Url::get(self::REDIRECT_SUCCESS . $productId),
        ]);
    }

    // -----------------------------------------------------------------------------

    /**
     * Reply Action
     *
     * @param  int $productId
     * @param  int $threadId
     *
     * @return Response
     */
    public function replyAction(int $productId, int $threadId): Response
    {
        $product = \Product::findFirstById($productId);

        if ( ! $productId || $product->hasPurchased() == false) {
            $this->flash->error('There is no record of your purchase for this item.');

            return $this->redirect(self::REDIRECT_FAILURE_PERMISSION);
        }

        $content = $this->request->getPost('content');

        $thread                    = new \ProductThreadReply();
        $thread->user_id           = $this->session->get('id');
        $thread->product_thread_id = $threadId;
        $thread->content           = $content;
        $result                    = $thread->save();

        if ( ! $result) {
            $this->flash->error($thread->getMessagesList());

            return $this->redirect(self::REDIRECT_FAILURE . $productId);
        }

        $url = \Library\Url::get('dashboard/question/index/' . $productId . '#thread-id-' . $threadId);

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

    // -----------------------------------------------------------------------------

    /**
     * @return @TODO
     */
    public function deleteAction()
    {
        $user_id = $this->session->get('user_id');

        return;
    }

}

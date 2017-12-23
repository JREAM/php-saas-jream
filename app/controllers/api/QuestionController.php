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

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * @TODO: Should just be a post requiring productId
     * @param int $productId
     *
     * @return Response
     */
    public function createAction(int $productId): Response
    {
        $this->apiMethods(['POST']);

        $product = \Product::findFirstById($productId);

        if (!$productId || $product->hasPurchased() == false) {
            return $this->output(0, 'You do not have permission to access this area.');
        }

        $title   = $this->json->title;
        $content = $this->json->content;

        $thread             = new \ProductThread();
        $thread->user_id    = $this->session->get('id');
        $thread->product_id = $productId;
        $thread->title      = $title;
        $thread->content    = $content;
        $result             = $thread->save();

        if (!$result) {
            return $this->output(0, $thread->getMessagesAsHTML());
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
        $mailResult = $this->di->get('email', [
            [
                'to_name'    => 'JREAM',
                'to_email'   => $this->config->email->to_question_address,
                'from_name'  => $this->config->email->from_name,
                'from_email' => $this->config->email->from_address,
                'subject'    => "JREAM - New Question ({$product->title})",
                'content'    => $content,
            ],
        ]);

        return $this->output(1, 'Your question has been added');
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * Reply Action
     *
     * @TODO: Should just be a post requiring productId
     *
     * @param  int $productId
     * @param  int $threadId
     *
     * @return Response
     */
    public function replyAction(int $productId, int $threadId): Response
    {
        $this->apiMethods(['POST']);

        $product = \Product::findFirstById($productId);

        if (!$productId || $product->hasPurchased() == false) {
            return $this->output(1, 'No record of your purchase of this item.');
        }

        $content = $this->json->content;

        $thread                    = new \ProductThreadReply();
        $thread->user_id           = $this->session->get('id');
        $thread->product_thread_id = $threadId;
        $thread->content           = $content;
        $result                    = $thread->save();

        if (!$result) {
            return $this->output(1, $thread->getMessagesAsHTML());
        }

        $url = \Library\Url::get('dashboard/question/index/' . $productId . '#thread-id-' . $threadId);

        // @TODO FIX EMAIL
        //$product = \Product::findFirstById($productId);
        //$content = $this->component->email->create('question-thread-reply', [
        //    'content'       => $content,
        //    'product_title' => $product->title,
        //    'url'           => $url,
        //]);
        //
        //$mailResult = $this->di->get('email', [
        //    [
        //        'to_name'    => 'JREAM',
        //        'to_email'   => $this->config->email->to_question_address,
        //        'from_name'  => $this->config->email->from_name,
        //        'from_email' => $this->config->email->from_address,
        //        'subject'    => "JREAM - Question Reply ({$product->title})",
        //        'content'    => $content,
        //    ],
        //]);

        return $this->output(1, 'Your reply was added.');
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * @return @TODO
     */
    public function deleteAction()
    {
        $this->apiMethods(['DELETE']);

        $user_id = $this->session->get('user_id');

        return;
    }
}

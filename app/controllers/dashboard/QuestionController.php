<?php

namespace Controllers\Dashboard;

use Phalcon\Tag;
use Phalcon\Mvc\View;
use Phalcon\Http\Response;
use Controllers\BaseController;

class QuestionController extends BaseController
{
    const REDIRECT_SUCCESS = 'dashboard/question/index/';
    const REDIRECT_FAILURE = 'dashboard/question/index/';
    const REDIRECT_FAILURE_PERMISSION = 'dashboard/';

    /**
     * @return void
     */
    public function onConstruct() : void
    {
        parent::initialize();
        Tag::setTitle('Questions | ' . $this->di['config']['title']);
    }

    // -----------------------------------------------------------------------------

    /**
     * @param int $productId
     *
     * @return View
     */
    public function indexAction(int $productId) : View
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

        return $this->view->pick('dashboard/question');
    }

}

<?php

declare(strict_types=1);

namespace Controllers;

use \Phalcon\Tag;
use Phalcon\Mvc\View;
use Phalcon\Http\Response;

class IndexController extends BaseController
{

    /**
     * @return void
     */
    public function onConstruct() : void
    {
        parent::initialize();
        // new \Library\Obj;
        Tag::setTitle('Learn to Code | ' . $this->di['config']['title']);
    }

    // -----------------------------------------------------------------------------

    /**
     * @return View
     */
    public function indexAction() : View
    {
        $products = \Product::find(["is_deleted = 0"]);

        $this->view->setVars([
            'products' => $products,
        ]);

        return $this->view->pick('index/index');
    }

    // -----------------------------------------------------------------------------

    /**
     * @return View
     */
    public function labAction() : View
    {
        return Tag::setTitle('Lab | ' . $this->di['config']['title']);
    }

    // -----------------------------------------------------------------------------

    /**
     * @return View
     */
    public function updatesAction() : View
    {
        # Updates
        $parsedown = new \Parsedown();
        $updates = file_get_contents(__DIR__ . '/../updates.md');

        Tag::setTitle('Updates | ' . $this->di['config']['title']);

        $this->view->setVars([
            'updates' => $parsedown->parse($updates),
        ]);

        return $this->view->pick('index/updates');
    }

    // -----------------------------------------------------------------------------

    /**
     * @return View
     */
    public function termsAction() : View
    {
        Tag::setTitle('Terms and Privacy | ' . $this->di['config']['title']);

        return $this->view->pick('index/terms');
    }

    // -----------------------------------------------------------------------------

    /**
     * @return void
     */
    public function show404Action() : void
    {
        Tag::setTitle('404 Not Found | ' . $this->di['config']['title']);
    }

    // -----------------------------------------------------------------------------

    /**
     * @return void
     */
    public function show503Action() : void
    {
        Tag::setTitle('503 Service Error | ' . $this->di['config']['title']);
    }

    // -----------------------------------------------------------------------------

    /**
     * @return void
     */
    public function show500Action(\Exception $exception) : void
    {
    }

}

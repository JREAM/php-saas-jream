<?php

namespace Controllers;

use \Phalcon\Tag;

/**
 * @RoutePrefix("/")
 */
class IndexController extends BaseController
{

    /**
     * @return void
     */
    public function onConstruct()
    {
        parent::initialize();
        // new \Library\Obj;
        Tag::setTitle('Learn to Code | ' . $this->di['config']['title']);
    }

    // --------------------------------------------------------------

    /**
     * @Get(
     *     "/"
     * )
     *
     * @return void
     */
    public function indexAction()
    {
        $products = \Product::find(["is_deleted = 0"]);

        $this->view->setVars([
            'products' => $products,
            'tags'     => \Product::getAllByTags(),
        ]);

        $this->view->pick('index/index');
    }

    // --------------------------------------------------------------

    /**
     * @Get(
     *     "/"
     * )
     *
     * @return void
     */
    public function labAction()
    {
        Tag::setTitle('Lab | ' . $this->di['config']['title']);
    }

    // --------------------------------------------------------------

    /**
     * @Get(
     *     "/"
     * )
     *
     * @return void
     */
    public function updatesAction()
    {
        # Updates
        $parsedown = new \Parsedown();
        $updates = file_get_contents(__DIR__ . '/../updates.md');

        Tag::setTitle('Updates | ' . $this->di['config']['title']);
        $this->view->setVars([
            'updates' => $parsedown->parse($updates),
        ]);
    }

    // --------------------------------------------------------------

    /**
     * @Get(
     *     "/"
     * )
     *
     * @return void
     */
    public function termsAction()
    {
        Tag::setTitle('Terms and Privacy | ' . $this->di['config']['title']);
    }

    // --------------------------------------------------------------

    /**
     * @Get(
     *     "/"
     * )
     *
     * @return void
     */
    public function show404Action()
    {
        Tag::setTitle('404 Not Found | ' . $this->di['config']['title']);
    }

    // --------------------------------------------------------------

    /**
     * @Get(
     *     "/"
     * )
     *
     * @return void
     */
    public function show503Action()
    {
        Tag::setTitle('503 Service Error | ' . $this->di['config']['title']);
    }

    // --------------------------------------------------------------

    public function show500Action($exception)
    {
    }

    // --------------------------------------------------------------
}

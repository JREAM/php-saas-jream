<?php

use \Phalcon\Tag;

class IndexController extends \BaseController
{

    /**
     * @return void
     */
    public function onConstruct()
    {
        parent::initialize();
        Tag::setTitle('Learn to Code');
    }

    // --------------------------------------------------------------

    /**
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
     * @return void
     */
    public function labAction()
    {
        Tag::setTitle('Lab');
    }

    // --------------------------------------------------------------

    /**
     * @return void
     */
    public function updatesAction()
    {
        # Updates
        $parsedown = new \Parsedown();
        $updates = file_get_contents(__DIR__ . '/../updates.md');

        Tag::setTitle('Updates');
        $this->view->setVars([
            'updates' => $parsedown->parse($updates),
        ]);
    }

    // --------------------------------------------------------------

    /**
     * @return void
     */
    public function termsAction()
    {
        Tag::setTitle('Terms and Privacy');
    }

    // --------------------------------------------------------------

    /**
     * @return void
     */
    public function show404Action()
    {
        Tag::setTitle('404 Not Found');
    }

    // --------------------------------------------------------------

    /**
     * @return void
     */
    public function show503Action()
    {
        Tag::setTitle('503 Service Error');
    }

    // --------------------------------------------------------------

}

// End of File
// --------------------------------------------------------------

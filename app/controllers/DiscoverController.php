<?php
use \Phalcon\Tag;

class DiscoverController extends \BaseController
{

    /**
     * @return void
     */
    public function onConstruct()
    {
        parent::initialize();
        Tag::setTitle('Discover');
    }

    // --------------------------------------------------------------

    /**
     * @return void
     */
    public function indexAction()
    {
        Tag::setTitle('Discover | ' . $this->di['config']['title']);
        $this->view->pick('discover/discover');
    }

    // --------------------------------------------------------------

    /**
     * @return void
     */
    public function learnAction()
    {
        Tag::setTitle('Discover | Learn ' . $this->di['config']['title']);
    }

    // --------------------------------------------------------------

    /**
     * @return void
     */
    public function benefitAction()
    {
        Tag::setTitle('Discover | Benefit ' . $this->di['config']['title']);
    }

    // --------------------------------------------------------------

}

// End of File
// --------------------------------------------------------------
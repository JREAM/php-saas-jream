<?php
namespace Hire;
use \Phalcon\Tag;

class HireController extends \BaseController
{

    /**
     * @return void
     */
    public function onConstruct()
    {
        parent::initialize();
        Tag::setTitle('Hire JREAM');
    }

    // --------------------------------------------------------------

    /**
     * @return void
     */
    public function indexAction()
    {
        $this->view->pick("hire/hire");
    }

    // --------------------------------------------------------------

}

// End of File
// --------------------------------------------------------------

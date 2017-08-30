<?php
declare(strict_types=1);

namespace Controllers;

use \Phalcon\Tag;

class TestController extends BaseController
{

    /**
     * @return void
     */
    public function onConstruct()
    {
        parent::initialize();
        Tag::setTitle('Test');
    }

    /**
     * @return void
     */
    public function indexAction()
    {
        $u = new \User;
        echo $u->getSource();
    }
}

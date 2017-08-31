<?php
declare(strict_types=1);

namespace Controllers;

use \Phalcon\Tag;

class TestController extends BaseController
{

    /**
     * @return void
     */
    public function onConstruct() : void
    {
        parent::initialize();
        Tag::setTitle('Test');
    }

    // -----------------------------------------------------------------------------

    /**
     * @return void
     */
    public function indexAction() : void
    {
        $hashids = $this->di->get('hashids');
        echo $hashids->encodeHex(2);
    }

}

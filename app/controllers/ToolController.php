<?php

declare(strict_types=1);

namespace Controllers;

use Phalcon\Mvc\View;
use Phalcon\Http\Response;

use Phalcon\Tag;

class ToolController extends BaseController
{

    /**
     * @return void
     */
    public function onConstruct(): void
    {
        parent::initialize();
        Tag::setTitle('Tool');
    }

    // -----------------------------------------------------------------------------

    /**
     * @return View
     */
    public function indexAction()
    {
        return $this->view->pick('tool/tool');
    }

    // -----------------------------------------------------------------------------


}

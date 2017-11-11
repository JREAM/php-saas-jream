<?php

namespace Controllers\Dashboard;

use Phalcon\Tag;
use Phalcon\Mvc\View;
use Phalcon\Http\Response;
use Controllers\BaseController;

class SupportController extends BaseController
{

    const REDIRECT_SUCCESS = 'dashboard';
    const REDIRECT_FAILURE = 'dashboard/support';

    private $_types = [
        'error'   => 'Report an Error',
        'support' => 'General Support',
    ];

    // -----------------------------------------------------------------------------

    /**
     * @return void
     */
    public function onConstruct(): void
    {
        parent::initialize();
        Tag::setTitle('Support | ' . $this->di[ 'config' ][ 'title' ]);
    }

    // -----------------------------------------------------------------------------

    /**
     * @return View
     */
    public function indexAction(): View
    {
        $this->view->setVars([
            // Make sure the type is a forced
            'types' => $this->_types,
        ]);

        return $this->view->pick("dashboard/support");
    }
}

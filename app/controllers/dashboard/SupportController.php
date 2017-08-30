<?php

namespace Controllers\Dashboard;

use \Phalcon\Tag;
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
    public function onConstruct()
    {
        parent::initialize();
        Tag::setTitle('Support | ' . $this->di['config']['title']);
    }

    // -----------------------------------------------------------------------------

    /**
     * @return void
     */
    public function indexAction()
    {
        $this->view->setVars([
            // Make sure the type is a forced
            'types'    => $this->_types,
        ]);

        $this->view->pick("dashboard/support");
    }

}

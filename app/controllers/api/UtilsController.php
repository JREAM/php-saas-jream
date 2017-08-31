<?php
declare(strict_types=1);

namespace Controllers\Api;

use \User;
use \Promotion;

class UtilsController extends ApiController
{

    /**
     * @return void
     */
    public function onConstruct()
    {
        parent::initialize();
    }

    // -----------------------------------------------------------------------------

    /**
     * Renders a markdown preview
     *
     * @return  string  json
     */
    public function markdownAction()
    {
        if (!$this->session->has('id')) {
            throw new \DomainException('Only Logged in users can do this.');
        }

        $parsedown = new \Parsedown();
        $content = trim($this->request->getPost('content'));
        if ($content) {
            $content = $parsedown->parse($content);
        }

        return $this->output(1, $content);
    }
}

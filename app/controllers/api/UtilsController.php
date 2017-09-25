<?php

declare(strict_types=1);

namespace Controllers\Api;

use Phalcon\Http\Response;

class UtilsController extends ApiController
{

    public function onConstruct()
    {
        parent::initialize();
    }

    // -----------------------------------------------------------------------------

    /**
     * Renders a markdown preview
     *
     * @throws \DomainException
     *
     * @return  Response
     */
    public function markdownAction(): Response
    {
        if ( ! $this->session->has('id')) {
            throw new \DomainException('Only Logged in users can do this.');
        }

        $parsedown = new \Parsedown();
        $content   = trim($this->request->getPost('content'));
        if ($content) {
            $content = $parsedown->parse($content);
        }

        return $this->output(1, $content);
    }
}

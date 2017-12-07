<?php

declare(strict_types=1);

namespace Controllers;

use Phalcon\Tag;
use Phalcon\Mvc\View;
use Phalcon\Http\Response;

/**
 * @RoutePrefix("/newsletter")
 */
class NewsletterController extends BaseController
{
    /**
     * @return void
     */
    public function onConstruct(): void
    {
        parent::initialize();
        Tag::setTitle('Newsletter | ' . $this->di[ 'config' ][ 'title' ]);
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * @return View
     */
    public function indexAction(): View
    {
        $this->view->setVars([
            'form' => new \Forms\NewsletterForm(),
        ]);

        return $this->view->pick('newsletter/index');
    }
}

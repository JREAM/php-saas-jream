<?php

namespace Controllers\Dashboard;

use Phalcon\Tag;
use Phalcon\Mvc\View;
use Phalcon\Http\Response;
use Controllers\BaseController;

class YoutubeController extends BaseController
{

    const REDIRECT_SUCCESS        = '';
    const REDIRECT_FAILURE        = 'dashboard';
    const REDIRECT_FAILURE_COURSE = 'dashboard';

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * @return void
     */
    public function onConstruct(): void
    {
        parent::initialize();
        Tag::setTitle('Youtube Videos | ' . $this->di[ 'config' ][ 'title' ]);
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * @param mixed $youtubeId
     *
     * @return mixed
     */
    public function indexAction($youtubeId = false)
    {
        $video = \Youtube::findFirstById($youtubeId);

        if (!$youtubeId) {
            $this->flash->error('There is no record of this item.');

            return $this->redirect(self::REDIRECT_FAILURE);
        }

        $this->view->setVars([
            'youtube' => $video,
        ]);

        return $this->view->pick("dashboard/youtube");
    }
}

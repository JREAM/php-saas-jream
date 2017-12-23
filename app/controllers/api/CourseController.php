<?php

declare(strict_types=1);

namespace Controllers\Api;

use Phalcon\Http\Response;
use UserAction;

class CourseController extends ApiController
{

    public function onConstruct()
    {
        parent::initialize();
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * @return Response
     */
    public function updateProgressAction(): Response
    {
        $this->apiMethods(['POST']);
        $userId = $this->session->get('id');

        $productCourseId = (int) $this->json->contentId;
        $productId       = (int) $this->json->productId;
        $action          = $this->json->action;
        $value           = (int) $this->json->value;

        $userAction = new UserAction();
        $userAction = $userAction->getAction($action, $userId, $productCourseId);

        if ($userAction) {
            $userAction->value = (int) $value;
            $userAction->save();
            return $this->output(1, 'updated', ['value' => $value]);
        }

        $userAction                    = new UserAction();
        $userAction->action            = $action;
        $userAction->user_id           = $userId;
        $userAction->product_id        = $productId;
        $userAction->product_course_id = $productCourseId;
        $userAction->value             = $value;
        $userAction->save();

        if ($userAction->getMessages() == false) {
            return $this->output(1, 'updated', ['value' => $value]);
        }

        return $this->output(0, $userAction->getMessagesString());
    }
}

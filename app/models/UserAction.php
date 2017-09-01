<?php
declare(strict_types=1);

use Phalcon\Mvc\Model\Behavior\SoftDelete;
use Phalcon\Validation;
use Phalcon\Validation\Validator;

class UserAction extends BaseModel
{

    // -----------------------------------------------------------------------------

    /** @var array Saves on Memcached Queries */
    public static $_cache;

    private $_actions = [
        'hasWatched',
        'hasCompleted',
    ];

    // -----------------------------------------------------------------------------

    public function initialize()
    {
        /** DB Table Name */
        $this->setSource('user_action');

        $this->addBehavior(new SoftDelete([
            'field' => 'is_deleted',
            'value' => 1,
        ]));

        $this->belongsTo("user_id", "User", "id");
        $this->hasOne("product_course_id", "ProductCourse", "id");
    }

    // -----------------------------------------------------------------------------

    public function getAction($action, $userId, $productCourseId)
    {
        $userAction = \UserAction::findFirst([
            "product_course_id = :productCourseId:
            AND user_id = :userId:
            AND action = :action:",
            "bind" => [
                'productCourseId' => $productCourseId,
                'action'          => $action,
                'userId'          => $userId,
            ],
        ]);

        return $userAction;
    }

    // -----------------------------------------------------------------------------

}

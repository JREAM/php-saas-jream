<?php

declare(strict_types=1);

use Phalcon\Mvc\Model\Behavior\SoftDelete;
use Phalcon\Validation;
use Phalcon\Validation\Validator;

class UserAction extends BaseModel
{
    /**
     * @var Table Rows
     */
    public $id;
    public $user_id;
    public $product_id;
    public $product_course_id;
    public $action;
    public $value;
    public $is_deleted;
    public $deleted_at;
    public $created_at;
    public $updated_at;

    // -----------------------------------------------------------------------------

    /**
     * Phalcons std initializer when model is ready
     *
     * @return void
     */
    public function initialize(): void
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
            'product_course_id = :productCourseId:
            AND user_id = :userId:
            AND action = :action:',
            'bind' => [
                'productCourseId' => $productCourseId,
                'action'          => $action,
                'userId'          => $userId,
            ],
        ]);

        return $userAction;
    }

    // -----------------------------------------------------------------------------
}

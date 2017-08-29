<?php
declare(strict_types=1);

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class UserAction extends BaseModel
{

    /** @const SOURCE the table name */
    const SOURCE = 'user_action';

    /** @var array Saves on Memcached Queries */
    public static $_cache;

    private $_actions = [
        'hasWatched',
        'hasCompleted',
    ];

    // --------------------------------------------------------------

    public function initialize()
    {
        $this->addBehavior(new SoftDelete([
            'field' => 'is_deleted',
            'value' => 1,
        ]));

        $this->setSource(self::SOURCE);
        $this->belongsTo("user_id", "User", "id");
        $this->hasOne("product_course_id", "ProductCourse", "id");
    }

    // --------------------------------------------------------------

    /**
     * This fixes an odd bug.
     *
     * @return string Class Name in lowercase
     */
    public function getSource()
    {
        return self::SOURCE;
    }

    // --------------------------------------------------------------

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

    // --------------------------------------------------------------

    public function afterCreate()
    {
        $this->created_at = getDateTime();
        $this->save();
    }

    // --------------------------------------------------------------

    public function afterUpdate()
    {
        $this->created_at = getDateTime();
        $this->save();
    }

}

<?php
declare(strict_types=1);

use Phalcon\Mvc\Model\Behavior\SoftDelete;
use Phalcon\Validation;
use Phalcon\Validation\Validator;

class ProductCourseMeta extends BaseModel
{

    // -----------------------------------------------------------------------------

    /** @var array Saves on Memcached Queries */
    public static $_cache;

    public function initialize()
    {
        /** DB Table Name */
        $this->setSource('product_course_meta');

        $this->addBehavior(new SoftDelete([
            'field' => 'is_deleted',
            'value' => 1,
        ]));

        $this->belongsTo("product_course_id", "ProductCourse", "id");
    }

    // -----------------------------------------------------------------------------

    public function getTypeIcon($type)
    {
        switch ($type) {
            case 'text':
                return '<span class="glyphicon glyphicon-align-justify"></span>';
                break;
            case 'file':
                return '<span class="glyphicon glyphicon-download-alt"></span>';
                break;
            case 'link':
                return '<span class="glyphicon glyphicon-link"></span>';
                break;
        }
    }

    // -----------------------------------------------------------------------------

}

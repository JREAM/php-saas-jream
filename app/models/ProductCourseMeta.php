<?php

declare(strict_types=1);

use Phalcon\Mvc\Model\Behavior\SoftDelete;
use Phalcon\Validation;
use Phalcon\Validation\Validator;

class ProductCourseMeta extends BaseModel
{

    /**
     * @var Table Rows
     */
    public $id;
    public $product_course_id;
    public $type;
    public $resource;
    public $content;
    public $is_deleted;
    public $deleted_at;
    public $created_at;
    public $updated_at;

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * Phalcons std initializer when model is ready
     *
     * @return void
     */
    public function initialize(): void
    {
        /** DB Table Name */
        $this->setSource('product_course_meta');

        $this->addBehavior(new SoftDelete([
            'field' => 'is_deleted',
            'value' => 1,
        ]));

        $this->belongsTo("product_course_id", "ProductCourse", "id");
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

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
            default:
                return '[icon-missing]';
        }
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
}

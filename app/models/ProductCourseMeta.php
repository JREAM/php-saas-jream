<?php
declare(strict_types=1);

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class ProductCourseMeta extends BaseModel
{

    /** @const SOURCE the table name */
    const SOURCE = 'product_course_meta';

    /** @var array Saves on Memcached Queries */
    public static $_cache;

    public function initialize()
    {
        $this->addBehavior(new SoftDelete([
            'field' => 'is_deleted',
            'value' => 1,
        ]));

        $this->setSource(self::SOURCE);
        $this->belongsTo("product_course_id", "ProductCourse", "id");
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

    // --------------------------------------------------------------

}

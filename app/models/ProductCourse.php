<?php
use Phalcon\Mvc\Model\Behavior\SoftDelete;

class ProductCourse extends BaseModel
{

    /** @const SOURCE the table name */
    const SOURCE = 'product_course';

    /** @var array Saves on Memcached Queries */
    public static $_cache;

    public function initialize()
    {
        $this->addBehavior(new SoftDelete([
            'field' => 'is_deleted',
            'value' => 1
        ]));

        $this->setSource(self::SOURCE);
        $this->belongsTo("product_id", "Product", "id");
        $this->hasMany("id", "UserAction", "product_course_id");
        $this->hasMany("id", "ProductCourseMeta", "product_course_id");
        $this->hasMany("id", "ProductCourseSection", "product_id");
    }

   // --------------------------------------------------------------

    /**
     * This fixes an odd bug.
     * @return string Class Name in lowercase
     */
    public function getSource()
    {
        return self::SOURCE;
    }

    // --------------------------------------------------------------

}

// End of File
// --------------------------------------------------------------
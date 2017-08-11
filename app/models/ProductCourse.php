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
            'value' => 1,
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
     *
     * @return string Class Name in lowercase
     */
    public function getSource()
    {
        return self::SOURCE;
    }

    // --------------------------------------------------------------

    /**
     * Gets the Previous Course in a Product Series
     *
     * @param  int    $product_id
     * @param  int    $section
     * @param  int    $course
     *
     * @return obj|int
     */
    public function getPrevCourse(int $product_id, int $section, int $course)
    {
        return $this->_getSingleCourse('prev', $product_id, $section, $course);
    }

    // --------------------------------------------------------------

    /**
     * Gets the Next Course in a Product Series
     *
     * @param  int    $product_id
     * @param  int    $section
     * @param  int    $course
     *
     * @return obj|int
     */
    public function getNextCourse(int $product_id, int $section, int $course)
    {
        return $this->_getSingleCourse('next', $product_id, $section, $course);
    }

    // --------------------------------------------------------------

    /**
     * Gets the Next or Prev Course in a Product Series
     *
     * @param  string $nextOrPrev Only accepts 'next' or 'prev'
     * @param  int    $product_id
     * @param  int    $section
     * @param  int    $course
     *
     * @return obj|int
     */
    private function _getSingleCourse(string $nextOrPrev='next', int $product_id, int $section, int $course)
    {

        if (!in_array($nextOrPrev, ['next', 'prev'])) {
            throw new \InvalidArgumentException('ProductCourse Model must supply string: next or prev only.');
        }

        if ($nextOrPrev == 'next') {
            $try_section += 1;
            $try_course += 1;
            $order_mode = 'ASC'; // lowest ID first [Going Forwards]
        } elseif ($nextOrPrev == 'prev') {
            $try_section -= 1;
            $try_course -= 1;
            $order_mode = 'DESC'; //highest ID first [Going Backwards]
        }

        // [1] First see if a course is next within this section
        $result = $this->findFirst([
            'product_id = :product_id:
            AND section = :section:
            AND course = :course:
            ORDER BY course :order_mode:
            LIMIT 1
            ',
            'bind'    => [
                'product_id' => (int) $product_id,
                'section' => (int) $section,
                'course' => (int) $try_course,
                'order_mode' => $order_mode
            ]
        ]);

        if ($result->count()) {
            return $result;
        }

        // [2] Otherwise check a different Section with
        $result = $this->findFirst([
            'product_id = :product_id:
            AND section = :section:
            ORDER BY course :order_mode:
            LIMIT 1
            ',
            'bind'    => [
                'product_id' => (int) $product_id,
                'section' => (int) $try_section,
                'order_mode' => $order_mode
            ]
        ]);

        if ($result->count()) {
            return $result;
        }

        // [3] No Results, we are either at beginning or end of entire series.
        return false;
    }

    // --------------------------------------------------------------

}

// End of File
// --------------------------------------------------------------

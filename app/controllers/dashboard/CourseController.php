<?php

namespace Controllers\Dashboard;

use Phalcon\Tag;
use Phalcon\Mvc\View;
use Phalcon\Http\Response;
use Controllers\BaseController;

class CourseController extends BaseController
{
    const REDIRECT_FAILURE = 'dashboard';
    const REDIRECT_FAILURE_COURSE = 'course/index/';

    protected $sectionTitle = 'My Courses';

    // -----------------------------------------------------------------------------

    /**
     * @return void
     */
    public function onConstruct() : void
    {
        parent::initialize();
        Tag::setTitle($this->sectionTitle . ' | ' . $this->di['config']['title']);
    }

    // -----------------------------------------------------------------------------

    /**
     * @param int $productId
     *
     * @TODO this aint right View or Response here
     *
     * @return View
     */
    public function indexAction($productId = false) : View
    {
        $product = \Product::findFirstById($productId);

        if (!$productId || $product->hasPurchased() == false) {
            $this->flash->error('There is no record of your purchase for this item.');
            return $this->redirect(self::REDIRECT_FAILURE);
        }

        $courses = \ProductCourse::find([
            "product_id = :product_id:",
            "bind"  => [
                'product_id' => $product->id,
            ],
            "order" => 'section, course',
        ]);

        $this->view->setVars([
            'product'    => $product,
            'courses'    => $courses,
            'userAction' => new \UserAction(),
            'percent'    => $product->getProductPercent(),
        ]);

        return $this->view->pick("dashboard/course");
    }

    // -----------------------------------------------------------------------------

    /**
     * @param int $productId
     * @param int $contentId
     *
     * @return View
     */
    public function viewAction(int $productId, int $contentId) : View
    {
        $product = \Product::findFirstById($productId);

        if (!$productId || $product->hasPurchased() == false) {
            $this->flash->error('There is no record of your purchase for this item.');
            return $this->redirect(self::REDIRECT_FAILURE);
        }

        // Get Course list to show Prev/Next
        $courses = \ProductCourse::find([
            'product_id = :product_id:',
            'bind'    => [
                'product_id' => (int)$product->id,
            ],
            'orderBy' => 'section, course',
        ]);

        $next = false;
        $prev = false;
        $productCourse = false;
        foreach ($courses as $key => $course) {
            if ($course->id == $contentId) {
                // The course being viewed
                $productCourse = $course;

                // For buttons to prev/next courses
                $next_key = $key + 1;
                if (isset($courses[$next_key])) {
                    $next = $courses[$next_key];
                }

                $prev_key = $key - 1;
                if (isset($courses[$prev_key])) {
                    $prev = $courses[$prev_key];
                }

                break;
            }
        }
        if (!$productCourse) {
            $this->flash->error('This content does not exist');

            return $this->redirect(self::REDIRECT_FAILURE_COURSE . "$productId");
        }

        $courseName = formatName($productCourse->name);
        $courseDescription = $productCourse->description;
        $productName = $productCourse->getProduct()->title;
        Tag::setTitle($this->sectionTitle . ' | ' . $courseName . ' > ' . $productName . ' | ' . $this->di['config']['title']);

        $rtmpSignedUrl = \ProductCourse::generateStreamUrl(
            $productCourse->getProduct()->path,
            $productCourse->name
        );

        $userAction = new \UserAction();
        $userAction = $userAction->getAction(
            'hasCompleted',
            $this->session->get('id'),
            $contentId
        );
        $hasCompleted = ($userAction) ? $userAction->value : 0;

        // ----------------------------
        // Set View Variables
        // ----------------------------
        $this->view->setVars([
            // Display
            'courseName'        => $courseName,
            'productName'       => $productName,
            'courseDescription' => $courseDescription,
            'hasCompleted'      => $hasCompleted,
            'productCourse'     => $productCourse,

            // Media Player
            'productUri'        => rtrim($productCourse->getProduct()->path, '/'),
            'rtmpSignedUrl'     => $rtmpSignedUrl,
            // 'signedUrl' => $signedUrl,
            'productId'         => $productId,
            'contentId'         => $contentId,

            // Navigation
            'next'              => $next,
            'prev'              => $prev,
        ]);

        return $this->view->pick("dashboard/course-view");
    }

}

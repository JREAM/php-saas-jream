<?php

namespace Dashboard;

use \Phalcon\Tag;

/**
 * @RoutePrefix("/dashboard/course")
 */
class CourseController extends \BaseController
{

    const REDIRECT_SUCCESS = '';
    const REDIRECT_FAILURE = 'dashboard';
    const REDIRECT_FAILURE_COURSE = 'course/index/';

    // --------------------------------------------------------------

    protected $sectionTitle = 'My Courses';

    /**
     * @return void
     */
    public function onConstruct()
    {
        parent::initialize();
        Tag::setTitle($this->sectionTitle . ' | ' . $this->di['config']['title']);
    }

    // --------------------------------------------------------------

    /**
     * @param integer $productId
     *
     * @return mixed
     */
    public function indexAction($productId = false)
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

            // CSRF
            'tokenKey'   => $this->security->getTokenKey(),
            'token'      => $this->security->getToken(),
        ]);

        $this->view->pick("dashboard/course");
    }

    // --------------------------------------------------------------

    /**
     * @param integer $productId
     * @param integer $contentId
     *
     * @return mixed
     */
    public function viewAction($productId, $contentId)
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

        $rtmpSignedUrl = $this->component->helper->generateStreamUrl(
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

            // CSRF
            'tokenKey'          => $this->security->getTokenKey(),
            'token'             => $this->security->getToken(),
        ]);

        $this->view->pick("dashboard/course-view");
    }

    // --------------------------------------------------------------

    /**
     * @return mixed
     */
    public function actionAction()
    {
        $this->view->disable();

        $productCourseId = (int)$this->request->getPost('contentId');
        $productId = (int)$this->request->getPost('productId');
        $action = $this->request->getPost('action');
        $value = (int)$this->request->getPost('value');

        $userAction = new \UserAction();
        $userAction = $userAction->getAction(
            $action,
            $this->session->get('id'),
            $productCourseId
        );

        if ($userAction) {
            $userAction->value = (int)$value;
            $userAction->save();
            $this->output(1, ['value' => $value]);
        }

        $userAction = new \UserAction();
        $userAction->action = $action;
        $userAction->user_id = $this->session->get('id');
        $userAction->product_id = $productId;
        $userAction->product_course_id = $productCourseId;
        $userAction->value = $value;
        $userAction->save();

        if ($userAction->getMessages() == false) {
            $this->output(1, ['value' => $value]);
            return true;
        } else {
            $this->output(0, $userAction->getMessagesString());
            return false;
        }
    }

    // --------------------------------------------------------------
}

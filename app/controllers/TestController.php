<?php

namespace App\Controllers;

use Phalcon\Tag;

/**
 * @RoutePrefix("/test")
 */
class TestController extends BaseController
{

    /**
     * @return void
     */
    public function onConstruct()
    {
        if (\APPLICATION_ENV == \APP_PRODUCTION && !$this->input->get('jesse=1')) {
            return $this->redirect('index');
        }
    }

    // calls the API
    public function ajaxAction()
    {
        $this->view->pick('test/ajax');
    }

    public function routeAction()
    {
//        echo '<pre>';
//        $r = $this->router->getRoutes();
//        print_r($r);
        die;
    }

    public function modelAction()
    {
        \App\Models\Newsletter::findFirst(['is_deleted = 0']);
        \App\Models\Notification::findFirst(['is_deleted = 0']);
        \App\Models\Product::findFirst(['is_deleted = 0']);
        \App\Models\ProductCourse::findFirst(['is_deleted = 0']);
        \App\Models\ProductCourseMeta::findFirst(['is_deleted = 0']);
        \App\Models\Promotion::findFirst(['is_deleted = 0']);
        \App\Models\ProductThread::findFirst(['is_deleted = 0']);
        \App\Models\ProductThreadReply::findFirst(['is_deleted = 0']);
        \App\Models\Transaction::findFirst(['is_deleted = 0']);
        \App\Models\User::findFirst(['is_deleted = 0']);
        \App\Models\UserAction::findFirst(['is_deleted = 0']);
        \App\Models\UserPurchase::findFirst(['is_deleted = 0']);
        \App\Models\UserReferrer::findFirst(['id = 0']);
        \App\Models\UserSupport::findFirst(['is_deleted = 0']);
        \App\Models\Youtube::findFirst(['is_deleted = 0']);
    }

    public function keyAction()
    {
        echo '<pre>';
        print_r($this->api);
        die;
        $this->view->pick('test/key.volt');
    }

    // --------------------------------------------------------------

    public function flashAction()
    {
        echo 'flash?';
        $z = $this->flash->output();

        echo '<pre>';

        $this->flash->message('formData', ['name' => 'jesse', 'age' => 25]);
        $z = $this->flash->getMessages('formData');
        print_r($z);
    }

    // --------------------------------------------------------------

    public function dbAction()
    {
        $batch = new Batch('user_notification');
        $batch->setRows(['user_id', 'notification_id'])
            ->setValues([
                [23, 1],
                [24, 1],
                [25, 1],
                [26, 1],
                [27, 1],
                [28, 1],
                [29, 1],
                [30, 1],
            ])->insert();
    }
}

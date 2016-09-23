<?php
use \Phalcon\Tag;

class TestController extends \BaseController
{

    /**
     * @return void
     */
    public function onConstruct()
    {
        if (\STAGE == 'live' && ! $this->input->get('jesse=1') ) {
            return $this->redirect('index');
        }
    }

    public function modelAction() {
        \Notification::findFirst(['is_deleted = 0']);
        \Product::findFirst(['is_deleted = 0']);
        \ProductCourse::findFirst(['is_deleted = 0']);
        \ProductCourseMeta::findFirst(['is_deleted = 0']);
        \ProductPromo::findFirst(['is_deleted = 0']);
        \ProductThread::findFirst(['is_deleted = 0']);
        \ProductThreadReply::findFirst(['is_deleted = 0']);
        \Transaction::findFirst(['is_deleted = 0']);
        \User::findFirst(['is_deleted = 0']);
        \UserAction::findFirst(['is_deleted = 0']);
        \UserPurchase::findFirst(['is_deleted = 0']);
        \UserReferrer::findFirst(['id = 0']);
        \UserSupport::findFirst(['is_deleted = 0']);
        \Youtube::findFirst(['is_deleted = 0']);
    }

    public function keyAction()
    {
        echo '<pre>';
        print_r($this->api);
        die;
        $this->view->pick('test/key.volt');
    }

    // --------------------------------------------------------------

    public function indexAction()
    {
        $parsedown = new Parsedown();
        echo $parsedown->parse('#ya
        Hello _Parsedown_!'); # prints: <p>Hello <em>Parsedown</em>!</p>
        die;
        $this->view->pick('test/index.volt');
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
                    [23,1],
                    [24,1],
                    [25,1],
                    [26,1],
                    [27,1],
                    [28,1],
                    [29,1],
                    [30,1],
            ])->insert();

    }

    // --------------------------------------------------------------

    public function emailAction()
    {
        $e = $this->component->email->create('purchase', [
            'gateway' => 'PayPal',
            'transaction_id' => 123,
            'product_price' => 12,
            'product_title' => 1.55
        ]);

        echo $e;
        die;
    }

    // --------------------------------------------------------------

}

// End of File
// --------------------------------------------------------------

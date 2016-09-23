<?php
namespace Admin;
use \Phalcon\Tag;

class AdminController extends \BaseController
{
    const REDIRECT_SUCCESS = 'admin';
    const REDIRECT_FAILURE = 'admin';

    // --------------------------------------------------------------

    /**
     * @return void
     */
    public function onConstruct()
    {
        if (!$this->session->has('id') || $this->session->get('role') != 'admin') {
            $this->redirect('index');
        }

        parent::initialize();
        Tag::setTitle('Admin');
    }

    // --------------------------------------------------------------

    public function indexAction()
    {
        $registered_today    = $this->_getRegistations(0);
        $registered_week     = $this->_getRegistations(7);
        $registered_month    = $this->_getRegistations(30);
        $registered_3_month  = $this->_getRegistations(90);
        $registered_6_month  = $this->_getRegistations(180);
        $registered_all_time = \User::find()->count();

        $sales_today    = $this->_getSales('0');
        $sales_week     = $this->_getSales('7');
        $sales_month    = $this->_getSales('30');
        $sales_3_month  = $this->_getSales('90');
        $sales_6_month  = $this->_getSales('180');
        $sales_all_time = \Transaction::find()->count();

        $this->view->setVars([
            'registered_today'    => $registered_today,
            'registered_week'     => $registered_week,
            'registered_month'    => $registered_month,
            'registered_3_month'  => $registered_3_month,
            'registered_6_month'  => $registered_6_month,
            'registered_all_time' => $registered_all_time,
            'sales_today'    => $sales_today,
            'sales_week'     => $sales_week,
            'sales_month'    => $sales_month,
            'sales_3_month'  => $sales_3_month,
            'sales_6_month'  => $sales_6_month,
            'sales_all_time' => $sales_all_time
        ]);
        $this->view->pick('admin/admin');
    }

    // --------------------------------------------------------------

    private function _getRegistations($days_back)
    {
        return \User::find([
            "DATE_FORMAT(created_at, '%Y-%m-%d')  BETWEEN :date_past: AND :date_now:",
            'bind' => [
                'date_past' => date('Y-m-d', strtotime("-$days_back days")),
                'date_now' => date('Y-m-d')
            ]
        ])->count();
    }

    // --------------------------------------------------------------

    private function _getSales($days_back)
    {
        return \Transaction::find([
            "DATE_FORMAT(created_at, '%Y-%m-%d') BETWEEN :date_past: AND :date_now: AND gateway != :gateway:",
            'bind' => [
                'gateway' => 'free',
                'date_past' => date('Y-m-d', strtotime("-$days_back days")),
                'date_now' => date('Y-m-d')
            ]
        ])->count();
    }

}

// End of File
// --------------------------------------------------------------
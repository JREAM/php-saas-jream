<?php

use Phalcon\Cli\Task;

class DestroyTask extends Task
{

    // ----------------------------------------------------------------------------

    public function mainAction()
    {
        require dirname(__DIR__) . '/config/config.php';
        require dirname(__DIR__) . '/config/api.php';
        require dirname(__DIR__) . '/config/services.php';
        // $di->getShared('session')
        $session = $di->get('session');
        $result = $session->destroy();
        var_dump($result);
        echo ' - Destroying Persistent Session/Session Bag' . PHP_EOL;

        $this->session->destroy();
        $bag = new \Phalcon\Session\Bag();
        // print_r($bag);
        // die;

        // $di = Phalcon\Di::getDefault();
        // echo (int) $di->persistent->destroy();
        $this->persistent->destroy();
    }

    // ----------------------------------------------------------------------------

    /**
     * @param array $params
     */
    public function testAction(array $params)
    {
        echo sprintf('hello %s', $params[0]);

        echo PHP_EOL;

        echo sprintf('best regards, %s', $params[1]);

        echo PHP_EOL;
    }

    // ----------------------------------------------------------------------------
}

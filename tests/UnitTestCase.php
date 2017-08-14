<?php

use Phalcon\Di;
use Phalcon\Test\UnitTestCase as PhalconTestCase;

abstract class UnitTestCase extends PhalconTestCase
{
    /**
     * @var bool
     */
    private $_loaded = false;


    public function setUp()
    {
        parent::setUp();

        // Load any additional services that might be required during testing
        $di = Di::getDefault();
        // Get any DI components here. If you have a config, be sure to pass it to the parent

        $config = include CONFIG_DIR . "config.php";
        $api    = include CONFIG_DIR . "api.php";

        $di->setShared('config', function() use ($config) {
            return $config;
        });

        $di->setShared('api', function () use ($api) {
            return $api;
        });

        $this->di->set('db', function() {
          return new \Phalcon\Db\Adapter\Pdo\Mysql([
                "host" => getenv('UNITTEST_DB_ADAPTER'),
                "username" => getenv('UNITTEST_DB_USERNAME'),
                "password" => getenv('UNITTEST_DB_PASSWORD'),
                "dbname" => getenv('UNITTEST_DB_DATABASE')
            ]);
        });

        $this->di->set('modelsManager', function() {
          return new \Phalcon\Mvc\Model\Manager();
        });

        $this->di->set('modelsMetadata', function() {
          return new \Phalcon\Mvc\Model\Metadata\Memory();
        });

        $di->setShared('session', function () {
            $session = new \Phalcon\Session\Adapter\Files();

            $session->start();
            return $session;
        });


        $this->setDi($di);




        $this->_loaded = true;
    }

    /**
     * Reset the DI for Every Class to re-use on every method.
     */
    protected function tearDown()
    {
        $di = $this->getDI();
        $di::reset();
        parent::tearDown();
    }

    /**
     * Check if the test case is setup properly
     *
     * @throws \PHPUnit_Framework_IncompleteTestError;
     */
    public function __destruct()
    {
        if (!$this->_loaded) {
            throw new \PHPUnit_Framework_IncompleteTestError(
                "Please run parent::setUp()."
            );
        }
    }
}

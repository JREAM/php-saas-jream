<?php

namespace Test;

/**
 * Class UnitTest
 */
class PromotionControllerTest extends \UnitTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testSomethingAction()
    {
        $mock = $this->getMockBuilder('PromotionController')
            ->setMethods(['findFirst'])
            ->getMock();

        $mock->expects($this->once())
            ->method('findFirst')
            ->will($this->returnValue(null));

        // NOW Apply.
        $controller = new $mock();
    }

}

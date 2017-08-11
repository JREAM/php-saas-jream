<?php

namespace Test;

/**
 * Class UnitTest
 */
class PromotionControllerTest extends \UnitTestCase
{
    protected function setUp()
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

    public function testTestCase()
    {
        $this->assertEquals(
            "works",
            "works",
            "This is OK"
        );

        $this->assertEquals(
            "works",
            "works1",
            "This will fail"
        );
    }
}

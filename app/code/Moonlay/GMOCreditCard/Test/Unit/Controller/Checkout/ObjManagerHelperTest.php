<?php

namespace Moonlay\GMOCreditCard\Test\Unit\Controller\Checkout;

use Moonlay\GMOCreditCard\Controller\Checkout\ObjManagerHelper;
use PHPUnit\Framework\TestCase;

class ObjManagerHelperTest extends TestCase
{
    private $objManagerHelper;
    private $objectManagerInterfaceMock;
protected function setUp()
{
    $this->objectManagerInterfaceMock = $this->getMockBuilder(\Magento\Framework\ObjectManagerInterface::class)
        ->getMock();
    $this->objManagerHelper  = new ObjManagerHelper( );

}

    public function testGetObjManager()
    {

        $this->objManagerHelper->getObjManager();
    }

    public function testSetObjManager()
    {
        $this->objManagerHelper->setObjManager($this->objectManagerInterfaceMock);

    }
}

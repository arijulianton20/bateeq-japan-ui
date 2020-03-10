<?php

namespace Moonlay\GMOCreditCard\Test\Unit\Plugin;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use Moonlay\GMOCreditCard\Plugin\OrderSenderPlugin;
use PHPUnit\Framework\TestCase;

class OrderSenderPluginTest extends TestCase
{
    private $OrderSenderPluginMock;
    private $orderSenderMock;
    private $orderMock;
    private $orderInterfaceMock;


    public function setUp()
    {
        $this->orderInterfaceMock = $this->createMock(\Magento\Sales\Api\Data\OrderInterface::class);
        $this->orderMock = $this->getMockBuilder(\Magento\Sales\Model\Order::class)
            ->disableOriginalConstructor()
            ->setMethods(['getPayment', 'getMethodInstance', 'getCode'])
            ->getMock();

        $this->orderSenderMock = $this->createMock(\Magento\Sales\Model\Order\Email\Sender\OrderSender::class);
        $this->objectManagerHelper = new ObjectManagerHelper($this);
        $this->OrderSenderPluginMock = $this->objectManagerHelper->getObject(
            \Moonlay\GMOCreditCard\Plugin\OrderSenderPlugin::class,
            [

            ]
        );
    }


    public function testAroundSend()
    {
        $this->orderMock->expects($this->any())->method('getPayment')->willReturnSelf();
        $this->orderMock->expects($this->any())->method('getMethodInstance')->willReturnSelf();
        $this->orderMock->expects($this->any())->method('getCode')->willReturn('processing');

        $callback = function ($order, $forceSyncMode) {
            return true;
        };

        $actual = $this->OrderSenderPluginMock->aroundSend($this->orderSenderMock, $callback, $this->orderMock, true);
        $this->assertEquals(true, $actual);
    }

    public function testAroundSendFailed()
    {
        $this->orderMock->expects($this->any())->method('getPayment')->willReturnSelf();
        $this->orderMock->expects($this->any())->method('getMethodInstance')->willReturnSelf();
        $this->orderMock->expects($this->any())->method('getCode')->willReturn('gmo_creditcard');

        $callback = function ($order, $forceSyncMode) {
            return true;
        };

        $actual = $this->OrderSenderPluginMock->aroundSend($this->orderSenderMock, $callback, $this->orderMock, true);
        $this->assertEquals(false, $actual);
    }


}



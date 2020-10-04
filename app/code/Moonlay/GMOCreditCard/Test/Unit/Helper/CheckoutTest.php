<?php

namespace Moonlay\GMOCreditCard\Test\Unit\Helper;

use Magento\Checkout\Model\Session;
use Moonlay\GMOCreditCard\Helper\Checkout;
use PHPUnit\Framework\TestCase;

class CheckoutTest extends TestCase
{
    private $sessionMock;
    private $checkoutmock;
    private $orderMock;
    private $AbstractModelMock;

    protected function setUp()
    {
        $this->sessionMock =$this->createMock(Session::class);
        $this->sessionMock =$this->createMock(Session::class);
        $this->orderMock =$this->createMock(\Magento\Sales\Model\Order::class);
        $this->AbstractModelMock = $this->createMock(\Magento\Framework\Model\AbstractModel::class);

        $objectManagerHelper = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->checkoutmock = $objectManagerHelper->getObject(\Moonlay\GMOCreditCard\Helper\Checkout::class,
            [
                'session' => $this->sessionMock
            ]);

    }


    public function testCancelCurrentOrderFail()
    {
        $comment='anycomment';
        $this->sessionMock->expects($this->once())
            ->method('getLastRealOrder')
            ->willReturn($this->orderMock);
        $this->assertEquals(false, $this->checkoutmock->cancelCurrentOrder($comment));
    }

    public function testCancelCurrentOrderSucces()
    {
        $comment='anycomment';
        $this->sessionMock->expects($this->once())
            ->method('getLastRealOrder')
            ->willReturn($this->orderMock);

        $this->orderMock->expects($this->once())
            ->method('getId')
            ->willReturn(true);

        $this->orderMock->expects($this->once())
            ->method('save')
            ->willReturnSelf();


         $this->orderMock->expects($this->once())
             ->method('registerCancellation')
             ->willReturnSelf();
         $this->assertEquals(true, $this->checkoutmock->cancelCurrentOrder($comment));
    }


    public function testRestoreQuote()
    {
        $this->sessionMock->expects($this->once())
            ->method('restoreQuote')
            ->willReturn(true);

        $this->assertEquals(true, $this->checkoutmock->restoreQuote());
    }
}

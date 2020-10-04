<?php

namespace Moonlay\GMOCreditCard\Test\Unit\Controller\Checkout;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use Moonlay\GMOCreditCard\Controller\Checkout\Cancel;
use PHPUnit\Framework\TestCase;

class CancelTest extends TestCase
{
    private $orderMock;
    private $cancelObj;
    private $contextMock;
    private $orderFactoryMock;
    private $requestMock;
    private $messageManagerMock;
    private $redirectMock;
    private $responseMock;
    private $objectManagerHelper;

    protected function setUp()
    {
        $this->orderMock = $this->createMock(\Magento\Sales\Model\Order::class);
        $this->messageManagerMock = $this->createMock(\Magento\Framework\Message\ManagerInterface::class);
        $this->redirectMock = $this->getMockBuilder(\Magento\Framework\App\Response\RedirectInterface::class)
            ->getMock();

        $this->responseMock = $this->createMock(\Magento\Framework\App\ResponseInterface::class);

        $this->contextMock = $this->getMockBuilder(\Magento\Framework\App\Action\Context::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->orderFactoryMock = $this->getMockBuilder(\Magento\Sales\Model\OrderFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->requestMock = $this->getMockBuilder(\Magento\Framework\App\RequestInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMockForAbstractClass();

        $this->contextMock->expects($this->any())->method('getRequest')->willReturn($this->requestMock);
        $this->contextMock->expects($this->any())->method('getMessageManager')->willReturn($this->messageManagerMock);
        $this->contextMock->expects($this->any())->method('getRedirect')->willReturn($this->redirectMock);
        $this->contextMock->expects($this->any())->method('getResponse')->willReturn($this->responseMock);

        $this->objectManagerHelper = new ObjectManagerHelper($this);
        $this->cancelObj = $this->objectManagerHelper->getObject(
            Cancel::class,
            [
                'context' => $this->contextMock,
                'orderFactory' => $this->orderFactoryMock,
            ]
        );
    }

    public function testExecute()
    {
        $this->requestMock->expects($this->any())->method('get')->with('orderId')->willReturn(1);
        $this->orderFactoryMock->expects($this->any())->method('create')->willReturn($this->orderMock);

        $this->orderMock->expects($this->any())->method('loadByIncrementId')
            ->with('1')
            ->willReturnSelf();

        $this->orderMock->expects($this->any())->method('getId')
            ->willReturn(1);

        $this->messageManagerMock->expects($this->any())->method('addWarningMessage')
            ->with(__("You have successfully canceled your GMO Credit Card. Please click on 'Update Shopping Cart'."))
            ->willReturnSelf();

        $this->assertNull($this->cancelObj->execute());


    }
}

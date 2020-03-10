<?php

namespace Moonlay\GMOCreditCard\Test\Unit\Controller\Checkout;

use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use Magento\Sales\Model\OrderFactory;
use Moonlay\GMOCreditCard\Gateway\Config\Config;
use Moonlay\GMOCreditCard\Helper\Checkout;
use Moonlay\GMOCreditCard\Helper\Crypto;
use Moonlay\GMOCreditCard\Helper\Data;
use Psr\Log\LoggerInterface;
use  Moonlay\GMOCreditCard\Test\Unit\Controller\AbstractAction\Stub;

use PHPUnit\Framework\TestCase;

class AbstractActionTest extends TestCase
{
    private $gatewayConfigMock;
    private $checkoutSessionMock;
    private $contextMock;
    private $orderFactoryMock;
    private $cryptoHelperMock;
    private $dataHelperMock;
    private $checkoutHelperMock;
    private $loggerMock;
    private $payment;
    private $orderMock;
    private $scopeConfigInterfaceMock;

    protected function setUp()
    {
        $this->orderMock = $this->createMock(\Magento\Sales\Model\Order::class);

        $this->scopeConfigInterfaceMock = $this->getMockBuilder(\Magento\Framework\App\Config\ScopeConfigInterface::class)
        ->disableOriginalConstructor()
        ->getMock();

        $this->gatewayConfigMock = $this->getMockBuilder(\Moonlay\GMOCreditCard\Gateway\Config\Config::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->checkoutSessionMock = $this->getMockBuilder(\Magento\Checkout\Model\Session::class)
            ->disableOriginalConstructor()
            ->setMethods(['getLastRealOrderId'])
            ->getMock();
        $this->contextMock = $this->getMockBuilder(\Magento\Framework\App\Action\Context::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->orderFactoryMock = $this->getMockBuilder(\Magento\Sales\Model\OrderFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->cryptoHelperMock = $this->getMockBuilder(\Moonlay\GMOCreditCard\Helper\Crypto::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->dataHelperMock = $this->getMockBuilder(\Moonlay\GMOCreditCard\Helper\Data::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->checkoutHelperMock = $this->getMockBuilder(\Moonlay\GMOCreditCard\Helper\Checkout::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->loggerMock = $this->getMockBuilder(\Psr\Log\LoggerInterface::class)->getMock();

        $this->objectManagerHelper = new ObjectManagerHelper($this);
        $this->payment = $this->objectManagerHelper->getObject(
            Stub::class,
            [
                'gatewayConfig' => $this->gatewayConfigMock,
                'checkoutSession' => $this->checkoutSessionMock,
                'context' => $this->contextMock,
                'orderFactory' => $this->orderFactoryMock,
                'cryptoHelper' => $this->cryptoHelperMock,
                'scopeConfigInterface'=> $this->scopeConfigInterfaceMock,
                'dataHelper' => $this->dataHelperMock,
                'checkoutHelper' => $this->checkoutHelperMock,
                'logger' => $this->loggerMock,
            ]
        );
    }

    public function testGetContext()
    {
        $reflectionClass = new \ReflectionClass(Stub::class);
        $getContext = $reflectionClass->getMethod('getContext');
        $getContext->setAccessible(true);

        $this->assertNull(null, $getContext->invoke($this->payment, []));
    }

    public function testGetCheckoutSession()
    {
        $reflectionClass = new \ReflectionClass(Stub::class);
        $getCheckoutSession = $reflectionClass->getMethod('getCheckoutSession');
        $getCheckoutSession->setAccessible(true);

        $this->assertNull(null, $getCheckoutSession->invoke($this->payment, []));
    }

    public function testGetOrderFactory()
    {
        $reflectionClass = new \ReflectionClass(Stub::class);
        $getOrderFactory = $reflectionClass->getMethod('getOrderFactory');
        $getOrderFactory->setAccessible(true);

        $this->assertNull(null, $getOrderFactory->invoke($this->payment, []));
    }

    public function testGetCryptoHelper()
    {
        $reflectionClass = new \ReflectionClass(Stub::class);
        $getCryptoHelper = $reflectionClass->getMethod('getCryptoHelper');
        $getCryptoHelper->setAccessible(true);

        $this->assertNull(null, $getCryptoHelper->invoke($this->payment, []));
    }

    public function testGetDataHelper()
    {
        $reflectionClass = new \ReflectionClass(Stub::class);
        $getDataHelper = $reflectionClass->getMethod('getDataHelper');
        $getDataHelper->setAccessible(true);

        $this->assertNull(null, $getDataHelper->invoke($this->payment, []));
    }

    public function testGetCheckoutHelper()
    {
        $reflectionClass = new \ReflectionClass(Stub::class);
        $getCheckoutHelper = $reflectionClass->getMethod('getCheckoutHelper');
        $getCheckoutHelper->setAccessible(true);

        $this->assertNull(null, $getCheckoutHelper->invoke($this->payment, []));
    }

    public function testGetGatewayConfig()
    {
        $reflectionClass = new \ReflectionClass(Stub::class);
        $getGatewayConfig = $reflectionClass->getMethod('getGatewayConfig');
        $getGatewayConfig->setAccessible(true);

        $this->assertNull(null, $getGatewayConfig->invoke($this->payment, []));
    }

    public function testGetMessageManager()
    {
        $reflectionClass = new \ReflectionClass(Stub::class);
        $getMessageManager = $reflectionClass->getMethod('getMessageManager');
        $getMessageManager->setAccessible(true);

        $this->assertNull(null, $getMessageManager->invoke($this->payment, []));
    }

    public function testGetLogger()
    {
        $reflectionClass = new \ReflectionClass(Stub::class);
        $getLogger = $reflectionClass->getMethod('getLogger');
        $getLogger->setAccessible(true);

        $this->assertNull(null, $getLogger->invoke($this->payment, []));
    }

    public function testGetOrder()
    {
        $reflectionClass = new \ReflectionClass(Stub::class);
        $getOrder = $reflectionClass->getMethod('getOrder');
        $getOrder->setAccessible(true);

        $this->checkoutSessionMock->expects($this->any())->method('getLastRealOrderId')->willReturn('1');

        $this->orderFactoryMock->expects($this->any())->method('create')->willReturn($this->orderMock);

        $this->orderMock->expects($this->any())->method('loadByIncrementId')
            ->with('1')
            ->willReturnSelf();

        $this->orderMock->expects($this->any())->method('getId')
            ->willReturn(1);

        $this->assertInstanceOf(\Magento\Sales\Model\Order::class, $getOrder->invoke($this->payment));
        $this->assertSame($this->orderMock, $getOrder->invoke($this->payment));

    }

    public function testGetOrderEmpty()
    {
        $reflectionClass = new \ReflectionClass(Stub::class);
        $getOrder = $reflectionClass->getMethod('getOrder');
        $getOrder->setAccessible(true);

        $this->assertNull(null, $getOrder->invoke($this->payment, []));
    }

    public function testGetOrderById()
    {
        $incrementId = '1';
        $reflectionClass = new \ReflectionClass(Stub::class);
        $getOrderById = $reflectionClass->getMethod('getOrderById');
        $getOrderById->setAccessible(true);
        $this->orderFactoryMock->expects($this->any())->method('create')->willReturn($this->orderMock);

        $this->orderMock->expects($this->any())->method('loadByIncrementId')
            ->with('1')
            ->willReturnSelf();

        $this->orderMock->expects($this->any())->method('getId')
            ->willReturn(1);
        $this->assertInstanceOf(\Magento\Sales\Model\Order::class, $getOrderById->invoke($this->payment, $incrementId));
        $this->assertSame($this->orderMock, $getOrderById->invoke($this->payment, $incrementId));

    }

    public function testGetOrderByIdEmpty()
    {
        $incrementId = '0';
        $reflectionClass = new \ReflectionClass(Stub::class);
        $getOrderById = $reflectionClass->getMethod('getOrderById');
        $getOrderById->setAccessible(true);
        $this->orderFactoryMock->expects($this->any())->method('create')->willReturn($this->orderMock);

        $this->orderMock->expects($this->any())->method('loadByIncrementId')
            ->with('0')
            ->willReturnSelf();

        $this->orderMock->expects($this->any())->method('getId')
            ->willReturn(0);

        $this->assertNull(null, $getOrderById->invoke($this->payment, $incrementId));
    }

    public function testGetObjectManager()
    {
        $reflectionClass = new \ReflectionClass(Stub::class);
        $getObjectManager = $reflectionClass->getMethod('getObjectManager');
        $getObjectManager->setAccessible(true);
        $this->assertInstanceOf(\Magento\Framework\ObjectManagerInterface::class, $getObjectManager->invoke($this->payment, []));

    }

    public function testGetCustomerSupportEmail()
    {
        $reflectionClass = new \ReflectionClass(Stub::class);
        $getObjectManager = $reflectionClass->getMethod('getCustomerSupportEmail');
        $getObjectManager->setAccessible(true);

        $this->scopeConfigInterfaceMock->expects($this->any())->method('getValue')
            ->with('trans_email/ident_support/email',\Magento\Store\Model\ScopeInterface::SCOPE_STORE)
            ->willReturnSelf();


        $this->assertInstanceOf(\Magento\Framework\App\Config\ScopeConfigInterface::class, $getObjectManager->invoke($this->payment, []));

    }


}

<?php

namespace Moonlay\GMOCreditCard\Test\Unit\Gateway\Request;

use Magento\Checkout\Model\Session;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use Magento\Sales\Model\Order;
use Moonlay\GMOCreditCard\Gateway\Config\Config;
use Moonlay\GMOCreditCard\Gateway\Request\InitializationRequest;
use Psr\Log\LoggerInterface;
use PHPUnit\Framework\TestCase;

class InitializationRequestTest extends TestCase
{
    private $objectManagerHelper;
    private $configMock;
    private $loggerMock;
    private $sessionMock;
    private $initializationRequestMock;
    private $orderAdapterMock;
    private $orderMock;
    private $AddressAdapterInterfaceMock;


    protected function setUp()
    {
        $this->orderMock = $this->getMockBuilder(\Magento\Sales\Model\Order::class)
            ->disableOriginalConstructor()
            ->setMethods(['getOrder','setState'])
            ->getMock();

        $this->configMock = $this->getMockBuilder(\Moonlay\GMOCreditCard\Gateway\Config\Config::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->loggerMock = $this->getMockBuilder(\Psr\Log\LoggerInterface::class)
            ->getMockForAbstractClass();
        $this->sessionMock = $this->getMockBuilder(\Magento\Checkout\Model\Session::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->orderAdapterMock = $this->getMockBuilder(\Magento\Payment\Gateway\Data\Order\OrderAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->AddressAdapterInterfaceMock = $this->getMockBuilder(\Magento\Payment\Gateway\Data\AddressAdapterInterface::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $this->objectManagerHelper = new ObjectManagerHelper($this);
        $this->initializationRequestMock = $this->objectManagerHelper->getObject(
            InitializationRequest::class,
            [
                'config' => $this->configMock,
                'logger' => $this->loggerMock,
                'session' => $this->sessionMock
            ]
        );

    }

    public function testValidateQuoteAllSupported()
    {
        $this->orderAdapterMock->expects($this->any())->method('getGrandTotalAmount')->willReturn(5);

        $this->orderAdapterMock->expects($this->any())->method('getBillingAddress')->willReturn($this->AddressAdapterInterfaceMock);

        $this->orderAdapterMock->expects($this->any())->method('getShippingAddress')->willReturn($this->AddressAdapterInterfaceMock);

        $this->AddressAdapterInterfaceMock->expects($this->any())
            ->method('getCountryId')
            ->willReturnOnConsecutiveCalls('','','','');


        $refClass = new \ReflectionClass(InitializationRequest::class);
        $validateQuote = $refClass->getMethod('validateQuote');
        $validateQuote->setAccessible(true);
        $result = $validateQuote->invoke($this->initializationRequestMock, $this->orderAdapterMock);
        $this->assertEquals(true, $result);

    }


    public function testValidateQuoteOrderShipNotSupported()
    {
        $this->orderAdapterMock->expects($this->any())->method('getGrandTotalAmount')->willReturn(5);

        $this->orderAdapterMock->expects($this->any())->method('getBillingAddress')->willReturn($this->AddressAdapterInterfaceMock);

        $this->orderAdapterMock->expects($this->any())->method('getShippingAddress')->willReturn($this->AddressAdapterInterfaceMock);

        $this->AddressAdapterInterfaceMock->expects($this->any())
            ->method('getCountryId')
            ->willReturnOnConsecutiveCalls('','','yy','yy');


        $refClass = new \ReflectionClass(InitializationRequest::class);
        $validateQuote = $refClass->getMethod('validateQuote');
        $validateQuote->setAccessible(true);
        $result = $validateQuote->invoke($this->initializationRequestMock, $this->orderAdapterMock);
        $this->assertEquals(false, $result);

    }

    public function testValidateQuoteBillingAddressNotSupported()
    {
        $this->orderAdapterMock->expects($this->any())->method('getGrandTotalAmount')->willReturn(5);

        $this->configMock->expects($this->any())->method('getSpecificCountry')->willReturn('jpy,idr');
        $this->AddressAdapterInterfaceMock->expects($this->any())->method('getCountryId')->willReturn('jpy,idr');

        $this->orderAdapterMock->expects($this->any())->method('getBillingAddress')->willReturn($this->AddressAdapterInterfaceMock);

        $refClass = new \ReflectionClass(InitializationRequest::class);
        $validateQuote = $refClass->getMethod('validateQuote');
        $validateQuote->setAccessible(true);
        $result = $validateQuote->invoke($this->initializationRequestMock, $this->orderAdapterMock);
        $this->assertEquals(false, $result);

    }


    public function testValidateQuoteZeroPurchaseNotSupported()
    {
        $this->orderAdapterMock->expects($this->any())->method('getGrandTotalAmount')->willReturn(0);
        $refClass = new \ReflectionClass(InitializationRequest::class);
        $validateQuote = $refClass->getMethod('validateQuote');
        $validateQuote->setAccessible(true);
        $result = $validateQuote->invoke($this->initializationRequestMock, $this->orderAdapterMock);
        $this->assertEquals(false, $result);

    }

    public function testBuildStatePendingPayment()
    {
        $this->orderAdapterMock->expects($this->any())->method('getGrandTotalAmount')->willReturn(5);

        $this->orderAdapterMock->expects($this->any())->method('getBillingAddress')->willReturn($this->AddressAdapterInterfaceMock);

        $this->orderAdapterMock->expects($this->any())->method('getShippingAddress')->willReturn($this->AddressAdapterInterfaceMock);

        $this->AddressAdapterInterfaceMock->expects($this->any())
            ->method('getCountryId')
            ->willReturnOnConsecutiveCalls('','','','');

        $buildSubject = [
            'stateObject'=>  $this->orderMock,
            'payment'=> $this->orderMock,
        ];

        $this->orderMock->expects($this->any())->method('getOrder')->willReturn($this->orderAdapterMock);
        $this->orderMock->expects($this->any())->method('setState')->with(Order::STATE_PENDING_PAYMENT)->willReturn($this->orderMock);

        $expect = [ 'IGNORED' => [ 'IGNORED' ] ];
        $this->assertEquals($expect,$this->initializationRequestMock->build($buildSubject));
    }

    public function testBuildStateCancelPayment()
    {

        $buildSubject = [
            'stateObject'=>  $this->orderMock,
            'payment'=> $this->orderMock,
        ];

        $this->orderMock->expects($this->any())->method('getOrder')->willReturn($this->orderAdapterMock);
        $this->orderMock->expects($this->any())->method('setState')->with(Order::STATE_CANCELED)->willReturn($this->orderMock);

        $expect = [ 'IGNORED' => [ 'IGNORED' ] ];
        $this->assertEquals($expect,$this->initializationRequestMock->build($buildSubject));
    }
}

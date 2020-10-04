<?php

namespace Moonlay\GMOCreditCard\Test\Unit\Controller\Checkout;


use Exception;
use http\Exception\RuntimeException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use Magento\Sales\Model\Order;
use Moonlay\GMOCreditCard\Controller\Checkout\Index;
use PHPUnit\Framework\TestCase;


class IndexTest extends TestCase
{
    private $gatewayConfigMock;
    private $objectManagerHelper;
    private $dataHelperMock;
    private $checkoutHelperMock;
    private $IndexObj;
    private $orderFactoryMock;
    private $orderMock;
    private $checkoutSessionMock;
    private $cryptoHelper;
    private $redirectMock;
    private $responseMock;
    private $messageManagerMock;
    private $loggerMock;


    protected function setUp()
    {
        $this->redirectMock = $this->getMockBuilder(\Magento\Framework\App\Response\RedirectInterface::class)
            ->getMock();

        $this->responseMock = $this->createMock(\Magento\Framework\App\ResponseInterface::class);

        $this->gatewayConfigMock = $this->getMockBuilder(\Moonlay\GMOCreditCard\Gateway\Config\Config::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->dataHelperMock = $this->getMockBuilder(\Moonlay\GMOCreditCard\Helper\Data::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->checkoutHelperMock = $this->getMockBuilder(\Moonlay\GMOCreditCard\Helper\Checkout::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->checkoutSessionMock = $this->getMockBuilder(\Magento\Checkout\Model\Session::class)
            ->disableOriginalConstructor()
            ->setMethods(['getLastRealOrderId', 'getErrorMessage', 'unsErrorMessage'])
            ->getMock();

        $this->cryptoHelper = new \Moonlay\GMOCreditCard\Helper\Crypto();


        $this->contextMock = $this->getMockBuilder(\Magento\Framework\App\Action\Context::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->orderFactoryMock = $this->getMockBuilder(\Magento\Sales\Model\OrderFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->orderMock = $this->getMockBuilder(\Magento\Sales\Model\Order::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->loggerMock = $this->getMockBuilder(\Psr\Log\LoggerInterface::class)->getMock();
        $this->messageManagerMock = $this->getMockBuilder(\Magento\Framework\Message\ManagerInterface::class)->getMock();

        $this->contextMock->expects($this->any())->method('getRedirect')->willReturn($this->redirectMock);
        $this->contextMock->expects($this->any())->method('getResponse')->willReturn($this->responseMock);
        $this->contextMock->expects($this->any())->method('getMessageManager')->willReturn($this->messageManagerMock);


        $this->objectManagerHelper = new ObjectManagerHelper($this);
        $this->IndexObj = $this->objectManagerHelper->getObject(
            Index::class,
            [
                'gatewayConfig' => $this->gatewayConfigMock,
                'checkoutSession' => $this->checkoutSessionMock,
                'context' => $this->contextMock,
                'orderFactory' => $this->orderFactoryMock,
                'cryptoHelper' => $this->cryptoHelper,
                'dataHelper' => $this->dataHelperMock,
                'checkoutHelper' => $this->checkoutHelperMock,
                'logger' => $this->loggerMock,
            ]
        );

    }

    public function testGetGMOData()
    {
        $refClass = new \ReflectionClass(Index::class);
        $getGMOData = $refClass->getMethod('getGMOData');
        $getGMOData->setAccessible(true);

        $this->orderMock->expects($this->any())->method('getRealOrderId')
            ->willReturn('1');

        $this->orderMock->expects($this->any())->method('load')
            ->with('1')
            ->willReturnSelf();

        $this->orderMock->expects($this->any())->method('getCustomerId')
            ->willReturn(1);

        $this->gatewayConfigMock->expects($this->any())->method('getShopID')
            ->willReturn('1');

        $this->orderMock->expects($this->any())->method('getTotalDue')
            ->willReturn(4.0);

        $this->orderMock->expects($this->any())->method('getShippingAmount')
            ->willReturn(1);
        $this->orderMock->expects($this->any())->method('getCreatedAt')
            ->willReturn('1');

        $this->dataHelperMock->expects($this->any())->method('getCompleteUrl')
            ->willReturn('GMOCreditCard/checkout/success');

        $this->dataHelperMock->expects($this->any())->method('getCancelledUrl')
            ->with(1)
            ->willReturn('GMOCreditCard/checkout/cancel');

        $this->gatewayConfigMock->expects($this->any())->method('getSiteID')
            ->willReturn('1');

        $expect = [
            'ShopPassString' => '48f4c0dd7317870666dbd402090761ef',
            'ShopID' => '1',
            'OrderID' => '1',
            'Amount' => '3',
            'Tax' => '1',
            'DateTime' => '19691231160000',
            'RetURL' => 'GMOCreditCard/checkout/success',
            'CancelURL' => 'GMOCreditCard/checkout/cancel',
            'ClientField1' => null,
            'ClientField2' => null,
            'ClientField3' => null,
            'UserInfo' => 'pc',
            'RetryMax' => '10',
            'SessionTimeout' => '600',
            'Enc' => 'utf-8',
            'Lang' => 'ja',
            'Confirm' => '1',
            'UseCredit' => '1',
            'UseCvs' => '0',
            'UseEdy' => '0',
            'UseSuica' => '0',
            'UsePayEasy' => '0',
            'UsePayPal' => '0',
            'UseNetid' => '0',
            'UseWebMoney' => '0',
            'UseAu' => '0',
            'UseDocomo' => '0',
            'UseSb' => '0',
            'UseJibun' => '0',
            'UseJcbPreca' => '0',
            'TemplateNo' => '1',
            'JobCd' => 'CAPTURE',
            'ItemCode' => null,
            'SiteID' => '1',
            'MemberID' => null,
            'MemberPassString' => '5db985b0605b051b75e65e2611d32a58',
            'ReserveNo' => '1',
            'MemberNo' => 1,
            'RegisterDisp1' => null,
            'RegisterDisp2' => null,
            'RegisterDisp3' => null,
            'RegisterDisp4' => null,
            'RegisterDisp5' => null,
            'RegisterDisp6' => null,
            'RegisterDisp7' => null,
            'RegisterDisp8' => null,
            'ReceiptsDisp1' => null,
            'ReceiptsDisp2' => null,
            'ReceiptsDisp3' => null,
            'ReceiptsDisp4' => null,
            'ReceiptsDisp5' => null,
            'ReceiptsDisp6' => null,
            'ReceiptsDisp7' => null,
            'ReceiptsDisp8' => null,
            'ReceiptsDisp9' => null,
            'ReceiptsDisp10' => null,
            'ReceiptsDisp11' => null,
            'ReceiptsDisp12' => null,
            'ReceiptsDisp13' => null,
            'EdyAddInfo1' => null,
            'EdyAddInfo2' => null,
            'ItemName' => null,
            'SuicaAddInfo1' => null,
            'SuicaAddInfo2' => null,
            'SuicaAddInfo3' => null,
            'SuicaAddInfo4' => null,
            'Commodity' => null,
            'ServiceName' => null,
            'ServiceTel' => null,
            'DocomoDisp1' => null,
            'DocomoDisp2' => null,
            'PaymentTermSec' => null,
            'PayDescription' => null,
            'CarryInfo' => null,
        ];

        $result = $getGMOData->invoke($this->IndexObj, $this->orderMock);
        $this->assertEquals($expect, $result);
    }

    public function testPostToCheckout()
    {

        $checkoutUrlExample = 'htttp://example.com';
        $payloadExample = [
            "name" => "khabib_nuramagemedov",
            "gender" => "male",
            "country" => "russia",
        ];

        $refClass = new \ReflectionClass(Index::class);
        $postToCheckout = $refClass->getMethod('postToCheckout');
        $postToCheckout->setAccessible(true);
        $postToCheckout->invoke($this->IndexObj, $checkoutUrlExample, $payloadExample);
        $this->assertNull( $postToCheckout->invoke($this->IndexObj, $checkoutUrlExample, $payloadExample));
    }



    public function testExecuteOrderUnrecognized()
    {
        $this->checkoutSessionMock->expects($this->any())->method('getLastRealOrderId')->willReturn('1');

        $this->orderFactoryMock->expects($this->any())->method('create')->willReturn($this->orderMock);

        $this->orderMock->expects($this->any())->method('loadByIncrementId')
            ->with('1')
            ->willReturnSelf();

        $this->orderMock->expects($this->any())->method('getId')
            ->willReturn(1);

        $this->redirectMock->expects($this->any())->method('redirect')
            ->with($this->responseMock)
            ->willReturnSelf();

        $this->assertNull($this->IndexObj->execute());

    }


    public function testExecuteOrderWithStateCancle()
    {
        $this->checkoutSessionMock->expects($this->any())->method('getLastRealOrderId')->willReturn('1');

        $this->orderFactoryMock->expects($this->any())->method('create')->willReturn($this->orderMock);

        $this->orderMock->expects($this->any())->method('loadByIncrementId')
            ->with('1')
            ->willReturnSelf();

        $this->orderMock->expects($this->any())->method('getId')
            ->willReturn(1);

        $this->orderMock->expects($this->any())->method('getState')
            ->willReturn(Order::STATE_CANCELED);


        $this->checkoutSessionMock->expects($this->any())->method('getErrorMessage')
            ->willReturn('Error Message');

        $this->messageManagerMock->expects($this->any())->method('addWarningMessage')
            ->with('Error Message')
            ->willReturnSelf();

        $this->checkoutSessionMock->expects($this->any())->method('unsErrorMessage')
            ->willReturn('errorMessage');
        $this->assertNull($this->IndexObj->execute());

    }

    public function testExecuteOrderWithStatePending()
    {
        $this->checkoutSessionMock->expects($this->any())->method('getLastRealOrderId')->willReturn('1');

        $this->orderFactoryMock->expects($this->any())->method('create')->willReturn($this->orderMock);

        $this->orderMock->expects($this->any())->method('loadByIncrementId')
            ->with('1')
            ->willReturnSelf();

        $this->orderMock->expects($this->any())->method('getId')
            ->willReturn(1);

        $this->orderMock->expects($this->any())->method('getState')
            ->willReturn(Order::STATE_PENDING_PAYMENT);

        $this->orderMock->expects($this->any())->method('getRealOrderId')
            ->willReturn('1');

        $this->orderMock->expects($this->any())->method('load')
            ->with('1')
            ->willReturnSelf();

        $this->orderMock->expects($this->any())->method('getCustomerId')
            ->willReturn(1);

        $this->checkoutSessionMock->expects($this->any())->method('getErrorMessage')
            ->willReturn('Error Message');

        $this->messageManagerMock->expects($this->any())->method('addWarningMessage')
            ->with('Error Message')
            ->willReturnSelf();

        $this->checkoutSessionMock->expects($this->any())->method('unsErrorMessage')
            ->willReturn('errorMessage');
        $this->assertNull($this->IndexObj->execute());

    }


    public function testExecuteWithException()
    {
        $this->checkoutSessionMock->expects($this->any())->method('getLastRealOrderId')->willReturn('1');

        $this->orderFactoryMock->expects($this->any())->method('create')->willReturn($this->orderMock);

        $this->orderMock->expects($this->any())->method('loadByIncrementId')
            ->with('1')
            ->willReturnSelf();
        $this->assertNull($this->IndexObj->execute());

    }

}



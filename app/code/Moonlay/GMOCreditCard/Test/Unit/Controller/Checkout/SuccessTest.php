<?php

namespace Moonlay\GMOCreditCard\Test\Unit\Controller\Checkout;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Payment\Transaction;
use Moonlay\GMOCreditCard\Controller\Checkout\Success;
use PHPUnit\Framework\TestCase;

class SuccessTest extends TestCase
{
    private $requestMock;
    private $redirectMock;
    private $responseMock;
    private $orderMock;
    private $messageManagerMock;
    private $scopeConfigInterfaceMock;
    private $objectManagerMock;
    private $objManagerMock;
    private $statusMock;
    private $abstractCollectionMock;
    private $orderConfigMock;
    private $paymentMock;
    private $transactionMock;
private $orderSenderMock;
private $gatewayConfigMock;
private $invoiceServiceMock;
private $invoiceMock;
private $dbTransactionMock;



    protected function setUp()
    {

        $this->paymentMock = $this->getMockBuilder(\Magento\Sales\Model\Order\Payment::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->transactionMock = $this->getMockBuilder(\Magento\Sales\Model\Order\Payment\Transaction::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->abstractCollectionMock = $this->getMockBuilder(\Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->statusMock = $this->getMockBuilder(\Magento\Framework\Model\AbstractModel::class)
            ->disableOriginalConstructor()
            ->setMethods(['getResourceCollection'])
            ->getMockForAbstractClass();

        $this->orderSenderMock = $this->getMockBuilder(\Magento\Sales\Model\Order\Email\Sender\OrderSender::class)
            ->disableOriginalConstructor()
            ->getMock();


        $this->objectManagerMock = $this->getMockBuilder(\Magento\Framework\ObjectManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->gatewayConfigMock = $this->getMockBuilder(\Moonlay\GMOCreditCard\Gateway\Config\Config::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->checkoutSessionMock = $this->getMockBuilder(\Magento\Checkout\Model\Session::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->contextMock = $this->getMockBuilder(\Magento\Framework\App\Action\Context::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->orderFactoryMock = $this->getMockBuilder(\Magento\Sales\Model\OrderFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->redirectMock = $this->getMockBuilder(\Magento\Framework\App\Response\RedirectInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->cryptoHelperMock = new \Moonlay\GMOCreditCard\Helper\Crypto();

        $this->scopeConfigInterfaceMock = $this->getMockBuilder(\Magento\Framework\App\Config\ScopeConfigInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->dataHelperMock = $this->getMockBuilder(\Moonlay\GMOCreditCard\Helper\Data::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->checkoutHelperMock = $this->getMockBuilder(\Moonlay\GMOCreditCard\Helper\Checkout::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->loggerMock = $this->getMockBuilder(\Psr\Log\LoggerInterface::class)->getMock();


        $this->requestMock = $this->getMockBuilder(\Magento\Framework\App\RequestInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMockForAbstractClass();


        $this->responseMock = $this->getMockBuilder(\Magento\Framework\App\ResponseInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->orderMock = $this->getMockBuilder(\Magento\Sales\Model\Order::class)
            ->disableOriginalConstructor()
            ->setMethods(['loadByIncrementId', 'getId', 'getTotalDue', 'getState', 'canInvoice', 'getConfig','setState','setStatus','addStatusHistoryComment','setIsCustomerNotified','getPayment','save'])
            ->getMock();

        $this->orderConfigMock = $this->getMockBuilder(\Magento\Sales\Model\Order\Config::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->invoiceServiceMock = $this->getMockBuilder(\Magento\Sales\Model\Service\InvoiceService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->invoiceMock = $this->getMockBuilder(\Magento\Sales\Model\Order\Invoice::class)
            ->disableOriginalConstructor()
            ->getMock();


        $this->messageManagerMock = $this->getMockBuilder(\Magento\Framework\Message\ManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->dbTransactionMock = $this->getMockBuilder(\Magento\Framework\DB\Transaction::class)
            ->disableOriginalConstructor()
            ->getMock();



        $this->contextMock->expects($this->any())->method('getObjectManager')->willReturn($this->objectManagerMock);
        $this->contextMock->expects($this->any())->method('getRequest')->willReturn($this->requestMock);
        $this->contextMock->expects($this->any())->method('getRedirect')->willReturn($this->redirectMock);
        $this->contextMock->expects($this->any())->method('getResponse')->willReturn($this->responseMock);
        $this->contextMock->expects($this->any())->method('getMessageManager')->willReturn($this->messageManagerMock);

        $this->objManagerMock = $this->getMockBuilder(\Moonlay\GMOCreditCard\Controller\Checkout\ObjManagerHelper::class)
            ->disableOriginalConstructor()
            //->setMethods(['get'])
            ->getMock();


        $this->objectManagerHelper = new ObjectManagerHelper($this);
        $this->succesObj = $this->objectManagerHelper->getObject(
            \Moonlay\GMOCreditCard\Controller\Checkout\Success::class,
            [
                'gatewayConfig' => $this->gatewayConfigMock,
                'checkoutSession' => $this->checkoutSessionMock,
                'context' => $this->contextMock,
                'orderFactory' => $this->orderFactoryMock,
                'cryptoHelper' => $this->cryptoHelperMock,
                'scopeConfigInterface' => $this->scopeConfigInterfaceMock,
                'dataHelper' => $this->dataHelperMock,
                'checkoutHelper' => $this->checkoutHelperMock,
                'logger' => $this->loggerMock,
                'objManager' => $this->objManagerMock

            ]
        );


    }

    public function testExecuteInvalidSignature()
    {
        $response = [
            'ShopID' => 'any_shop_id'
        ];

        $this->requestMock->expects($this->any())->method('getParams')->willReturn($response);

        $this->requestMock->expects($this->any())->method('get')
            ->withConsecutive(
                ['ErrCode'],
                ['ErrInfo'],
                ['OrderID'],
                ['AccessID'])
            ->willreturnonconsecutivecalls('404', 'Error info', 1, 1);

        $this->succesObj->execute();
    }

    public function testExecuteErrorNullOrderId()
    {
        $response = [
            'ShopID' => null
        ];

        $this->requestMock->expects($this->any())->method('getParams')->willReturn($response);

        $this->requestMock->expects($this->any())->method('get')
            ->withConsecutive(
                ['ErrCode'],
                ['ErrInfo'],
                ['OrderID'],
                ['AccessID'])
            ->willreturnonconsecutivecalls('404', 'Error info', 0, 1);

        $this->succesObj->execute();
    }

    public function testExecuteBadId()
    {
        $response = [
            'ShopID' => null
        ];

        $this->requestMock->expects($this->any())->method('getParams')->willReturn($response);

        $this->requestMock->expects($this->any())->method('get')
            ->withConsecutive(
                ['ErrCode'],
                ['ErrInfo'],
                ['OrderID'],
                ['AccessID'])
            ->willreturnonconsecutivecalls('404', 'Error info', 1, 1);


        $this->orderFactoryMock->expects($this->any())->method('create')->willReturn($this->orderMock);
        $this->orderMock->expects($this->any())->method('loadByIncrementId')->with(1)->willReturn($this->orderMock);
        $this->orderMock->expects($this->any())->method('getId')->willReturn(0);
        $this->succesObj->execute();
    }

    public function testExecuteInvalidPayment()
    {
        $response = [
            'ShopID' => null
        ];

        $this->requestMock->expects($this->any())->method('getParams')->willReturn($response);

        $this->requestMock->expects($this->any())->method('get')
            ->withConsecutive(
                ['ErrCode'],
                ['ErrInfo'],
                ['OrderID'],
                ['AccessID'],
                ['Amount'])
            ->willreturnonconsecutivecalls('404', 'Error info', 1, 1, 10);


        $this->orderFactoryMock->expects($this->any())->method('create')->willReturn($this->orderMock);
        $this->orderMock->expects($this->any())->method('loadByIncrementId')->with(1)->willReturn($this->orderMock);
        $this->orderMock->expects($this->any())->method('getId')->willReturn(1);
        $this->orderMock->expects($this->any())->method('getTotalDue')->willReturn(1);


        $this->succesObj->execute();
    }

    public function testExecuteOrderRejected()
    {
        $response = [
            'ShopID' => null
        ];

        $this->requestMock->expects($this->any())->method('getParams')->willReturn($response);

        $this->requestMock->expects($this->any())->method('get')
            ->withConsecutive(
                ['ErrCode'],
                ['ErrInfo'],
                ['OrderID'],
                ['AccessID'],
                ['Amount'])
            ->willreturnonconsecutivecalls('404', 'Error info', 1, 1, 10);


        $this->orderFactoryMock->expects($this->any())->method('create')->willReturn($this->orderMock);
        $this->orderMock->expects($this->any())->method('loadByIncrementId')->with(1)->willReturn($this->orderMock);
        $this->orderMock->expects($this->any())->method('getId')->willReturn(1);
        $this->orderMock->expects($this->any())->method('getTotalDue')->willReturn(10);

        $this->messageManagerMock->expects($this->any())->method('addErrorMessage')->with(__("お支払いに問題がありました。後でもう一度やり直してください。"));

        $this->succesObj->execute();
    }


    public function testExecuteStateProcessing()
    {
        $response = [
            'ShopID' => null
        ];

        $this->requestMock->expects($this->any())->method('getParams')->willReturn($response);

        $this->requestMock->expects($this->any())->method('get')
            ->withConsecutive(
                ['ErrCode'],
                ['ErrInfo'],
                ['OrderID'],
                ['AccessID'],
                ['Amount'])
            ->willreturnonconsecutivecalls('', '', 1, 1, 10);

        $this->orderFactoryMock->expects($this->any())->method('create')->willReturn($this->orderMock);
        $this->orderMock->expects($this->any())->method('loadByIncrementId')->with(1)->willReturn($this->orderMock);
        $this->orderMock->expects($this->any())->method('getId')->willReturn(1);
        $this->orderMock->expects($this->any())->method('getTotalDue')->willReturn(10);
        $this->orderMock->expects($this->any())->method('getState')->willReturn('processing');
        $this->succesObj->execute();
    }

    public function testExecuteStateCanceled()
    {
        $response = [
            'ShopID' => null
        ];

        $this->requestMock->expects($this->any())->method('getParams')->willReturn($response);

        $this->requestMock->expects($this->any())->method('get')
            ->withConsecutive(
                ['ErrCode'],
                ['ErrInfo'],
                ['OrderID'],
                ['AccessID'],
                ['Amount'])
            ->willreturnonconsecutivecalls('404', 'Error info', 1, 1, 10);

        $this->orderFactoryMock->expects($this->any())->method('create')->willReturn($this->orderMock);
        $this->orderMock->expects($this->any())->method('loadByIncrementId')->with(1)->willReturn($this->orderMock);
        $this->orderMock->expects($this->any())->method('getId')->willReturn(1);
        $this->orderMock->expects($this->any())->method('getTotalDue')->willReturn(10);
        $this->orderMock->expects($this->any())->method('getState')->willReturn('canceled');
        $this->succesObj->execute();
    }

    public function testExecuteEmptyErrorAndCode()
    {

        $response = [
            'ShopID' => null
        ];

        $this->requestMock->expects($this->any())->method('getParams')->willReturn($response);
        $this->requestMock->expects($this->any())->method('get')
            ->withConsecutive(
                ['ErrCode'],
                ['ErrInfo'],
                ['OrderID'],
                ['AccessID'],
                ['Amount'],
                ['Tax'])
            ->willreturnonconsecutivecalls('', '', 1, 1, 5, 5);

        $this->orderFactoryMock->expects($this->any())->method('create')->willReturn($this->orderMock);
        $this->orderMock->expects($this->any())->method('loadByIncrementId')->with(1)->willReturn($this->orderMock);
        $this->orderMock->expects($this->any())->method('getId')->willReturn(1);
        $this->orderMock->expects($this->any())->method('getTotalDue')->willReturn(10);
//       $approvedStatus= $this->gatewayConfigMock->expects($this->any())->method('getApprovedOrderStatus')->willReturn('pending payment');
         $this->gatewayConfigMock->expects($this->any())->method('getApprovedOrderStatus')->willReturnSelf();


        $this->objManagerMock->expects($this->once())->method('get')->with('Magento\Sales\Model\Order\Status')->willReturn($this->statusMock);



        $this->statusMock->expects($this->once())->method('getResourceCollection')->willReturn($this->abstractCollectionMock);

        $obj=[
            'obj'=>[
                "status" => "reject"
            ]

        ];

        $this->abstractCollectionMock->expects($this->once())->method('getData')->willReturn($obj);

        $this->orderMock->expects($this->any())->method('getConfig')->willReturn($this->orderConfigMock);

        $this->orderMock->expects($this->any())->method('setState')->with(Order::STATE_PROCESSING)->willReturnSelf();
        $this->orderMock->expects($this->any())->method('setStatus')->with(null)->willReturnSelf();
        $this->orderMock->expects($this->any())->method('addStatusHistoryComment')->with("GMO Credit Card authorisation success. Transaction #1")->willReturnSelf();
        $this->orderMock->expects($this->any())->method('getPayment')->willReturn($this->paymentMock);
        $this->paymentMock->expects($this->any())->method('addTransaction')
            ->with(Transaction::TYPE_CAPTURE,null,true)
            ->willReturn($this->transactionMock);
        $this->orderMock->expects($this->any())->method('save')->willReturnSelf();

//        $this->objManagerMock->expects($this->once())->method('create')->with('\Magento\Sales\Model\Order\Email\Sender\OrderSender')->willReturn($this->orderSenderMock);

        $this->objManagerMock->expects($this->atLeastOnce())->method('create')
            ->withConsecutive(['\Magento\Sales\Model\Order\Email\Sender\OrderSender'],
                ['Magento\Sales\Model\Service\InvoiceService'],['Magento\Framework\DB\Transaction'])
            ->willReturnOnConsecutiveCalls($this->orderSenderMock,$this->invoiceServiceMock,$this->dbTransactionMock);

        $this->gatewayConfigMock->expects($this->once())->method('isAutomaticInvoice')->willReturn(true);

        $this->orderMock->expects($this->any())->method('canInvoice')->willReturn(true);

        $this->invoiceServiceMock->expects($this->any())->method('prepareInvoice')->with($this->orderMock)->willReturn($this->invoiceMock);

        $this->invoiceMock->expects($this->any())->method('getTotalQty')->willReturn(1);
  $this->invoiceMock->expects($this->any())->method('getOrder')->willReturn($this->orderMock);

        $this->dbTransactionMock->expects($this->any())
            ->method('addObject')
            ->withConsecutive([$this->invoiceMock],[$this->orderMock])->willReturnOnConsecutiveCalls($this->dbTransactionMock,$this->dbTransactionMock);
        $this->succesObj->execute();
    }


    public function testExecuteInvoiceWithoutProduct()
    {

        $response = [
            'ShopID' => null
        ];

        $this->requestMock->expects($this->any())->method('getParams')->willReturn($response);
        $this->requestMock->expects($this->any())->method('get')
            ->withConsecutive(
                ['ErrCode'],
                ['ErrInfo'],
                ['OrderID'],
                ['AccessID'],
                ['Amount'],
                ['Tax'])
            ->willreturnonconsecutivecalls('', '', 1, 1, 5, 5);

        $this->orderFactoryMock->expects($this->any())->method('create')->willReturn($this->orderMock);
        $this->orderMock->expects($this->any())->method('loadByIncrementId')->with(1)->willReturn($this->orderMock);
        $this->orderMock->expects($this->any())->method('getId')->willReturn(1);
        $this->orderMock->expects($this->any())->method('getTotalDue')->willReturn(10);
//       $approvedStatus= $this->gatewayConfigMock->expects($this->any())->method('getApprovedOrderStatus')->willReturn('pending payment');
        $this->gatewayConfigMock->expects($this->any())->method('getApprovedOrderStatus')->willReturnSelf();


        $this->objManagerMock->expects($this->once())->method('get')->with('Magento\Sales\Model\Order\Status')->willReturn($this->statusMock);



        $this->statusMock->expects($this->once())->method('getResourceCollection')->willReturn($this->abstractCollectionMock);

        $obj=[
            'obj'=>[
                "status" => "reject"
            ]

        ];

        $this->abstractCollectionMock->expects($this->once())->method('getData')->willReturn($obj);

        $this->orderMock->expects($this->any())->method('getConfig')->willReturn($this->orderConfigMock);

        $this->orderMock->expects($this->any())->method('setState')->with(Order::STATE_PROCESSING)->willReturnSelf();
        $this->orderMock->expects($this->any())->method('setStatus')->with(null)->willReturnSelf();
        $this->orderMock->expects($this->any())->method('addStatusHistoryComment')->with("GMO Credit Card authorisation success. Transaction #1")->willReturnSelf();
        $this->orderMock->expects($this->any())->method('getPayment')->willReturn($this->paymentMock);
        $this->paymentMock->expects($this->any())->method('addTransaction')
            ->with(Transaction::TYPE_CAPTURE,null,true)
            ->willReturn($this->transactionMock);
        $this->orderMock->expects($this->any())->method('save')->willReturnSelf();

//        $this->objManagerMock->expects($this->once())->method('create')->with('\Magento\Sales\Model\Order\Email\Sender\OrderSender')->willReturn($this->orderSenderMock);

        $this->objManagerMock->expects($this->atLeastOnce())->method('create')
            ->withConsecutive(['\Magento\Sales\Model\Order\Email\Sender\OrderSender'],
                ['Magento\Sales\Model\Service\InvoiceService'],['Magento\Framework\DB\Transaction'])
            ->willReturnOnConsecutiveCalls($this->orderSenderMock,$this->invoiceServiceMock,$this->dbTransactionMock);

        $this->gatewayConfigMock->expects($this->once())->method('isAutomaticInvoice')->willReturn(true);

        $this->orderMock->expects($this->any())->method('canInvoice')->willReturn(true);

        $this->invoiceServiceMock->expects($this->any())->method('prepareInvoice')->with($this->orderMock)->willReturn($this->invoiceMock);

        $this->invoiceMock->expects($this->any())->method('getTotalQty')->willReturn(0);

        $this->expectException( \Magento\Framework\Exception\LocalizedException::class);
        $this->succesObj->execute();
    }


    public function testinvoiceOrderCantcreate()
    {
        $refClass = new \ReflectionClass(Success::class);
        $invoiceOrder = $refClass->getMethod('invoiceOrder');
        $invoiceOrder->setAccessible(true);
        $this->orderMock->expects($this->any())->method('canInvoice')->willReturn(false);
        $this->expectException(\Magento\Framework\Exception\LocalizedException::class);
        $invoiceOrder->invoke($this->succesObj, $this->orderMock, 1);

    }

    public function testinvoiceOrder()
    {
        $refClass = new \ReflectionClass(Success::class);
        $invoiceOrder = $refClass->getMethod('invoiceOrder');
        $invoiceOrder->setAccessible(true);
        $this->orderMock->expects($this->any())->method('canInvoice')->willReturn(true);

        //   $invoiceOrder->invoke($this->succesObj,$this->orderMock,1 );

    }

    public function testStatusExists()
    {
        $refClass = new \ReflectionClass(Success::class);
        $statusExists = $refClass->getMethod('statusExists');
        $statusExists->setAccessible(true);

        // $this->statusMock->expects($this->once())->method('getResourceCollection')->willReturnSelf();

        //$statusExists->invoke($this->succesObj,'canceled' );

    }

    public function testcheckTotalDueShouldTrue()
    {
        $refClass = new \ReflectionClass(Success::class);
        $checkTotalDue = $refClass->getMethod('checkTotalDue');
        $checkTotalDue->setAccessible(true);
        $result = $checkTotalDue->invoke($this->succesObj, 1, 1);
        $this->assertTrue($result);
    }

    public function testcheckTotalDueShouldFalse()
    {
        $refClass = new \ReflectionClass(Success::class);
        $checkTotalDue = $refClass->getMethod('checkTotalDue');
        $checkTotalDue->setAccessible(true);
        $result = $checkTotalDue->invoke($this->succesObj, 1, 2);
        $this->assertFalse($result);
    }
}

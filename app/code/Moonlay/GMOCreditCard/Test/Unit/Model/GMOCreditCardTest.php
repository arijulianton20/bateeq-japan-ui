<?php

namespace Moonlay\GMOCreditCard\Test\Unit\Model;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use Moonlay\GMOCreditCard\Model\GMOCreditCard;
use PHPUnit\Framework\TestCase;

class GMOMultipaymentTest extends TestCase
{

    private $contextMock;
    private $registryMock;
    private $extensionAttributesFactoryMock;
    private $attributeValueFactoryMock;
    private $dataMock;
    private $scopeConfigInterfaceMock;
    private $loggerMock;
    private $GMOCreditCardMock;
    private $objectManagerHelper;
    private $orderMock;
    private $orderPaymentInterfaceMock;


    public function setup()
    {
        $this->orderPaymentInterfaceMock = $this->getMockBuilder(\Magento\Sales\Api\Data\OrderPaymentInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getData'])
            ->getMockForAbstractClass();


        $this->contextMock = $this->getMockBuilder(\Magento\Framework\Model\Context::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->registryMock = $this->getMockBuilder(\Magento\Framework\Registry::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->extensionAttributesFactoryMock = $this->getMockBuilder(\Magento\Framework\Api\ExtensionAttributesFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->dataMock = $this->getMockBuilder(\Magento\Payment\Helper\Data::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->scopeConfigInterfaceMock = $this->getMockBuilder(\Magento\Framework\App\Config\ScopeConfigInterface::class)
            ->getMockForAbstractClass();

        $this->attributeValueFactoryMock = $this->getMockBuilder(\Magento\Framework\Api\AttributeValueFactory::class)
            ->disableOriginalConstructor()
            ->getmock();

        $this->loggerMock = $this->getMockBuilder(\Magento\Payment\Model\Method\Logger::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->orderMock = $this->getMockBuilder(\Magento\Sales\Api\Data\OrderInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getData', 'getPayment'])
            ->getMockForAbstractClass();

        $this->objectManagerHelper = new ObjectManagerHelper($this);
        $this->GMOCreditCardMock = $this->objectManagerHelper->getObject(
            \Moonlay\GMOCreditCard\Model\GMOCreditCard::class,
            [
                'context' => $this->contextMock,
                'registry' => $this->registryMock,
                'extensionAttributesFactory' => $this->extensionAttributesFactoryMock,
                'attributeValueFactory' => $this->attributeValueFactoryMock,
                'data' => $this->dataMock,
                'scopeConfigInterface' => $this->scopeConfigInterfaceMock,
                'logger' => $this->loggerMock
            ]
        );
    }

//    public function testHandle()
//    {
//        $handlingSubject = [
//            'amount' => 1,
//            'payment' => $this->orderMock,
//            'creditmemo' => $this->orderPaymentInterfaceMock,
//            'invoice' => $this->orderPaymentInterfaceMock,
//            'transaction_id' => $this->orderPaymentInterfaceMock,
//
//        ];
//        $response = [
//            'GATEWAY_REFUND_GATEWAY_URL' => 'http://example-refund-url.com',
//            'GATEWAY_MERCHANT_ID' => '',
//            'GATEWAY_API_KEY' => ''
//        ];
//        $this->orderMock->expects($this->any())->method('getPayment')->willReturn($this->orderPaymentInterfaceMock);
//
//        $this->orderPaymentInterfaceMock->expects($this->any())->method('getData')
//            ->withConsecutive(['creditmemo'], [], ['invoice'], ['transaction_id'])
//            ->willReturnOnConsecutiveCalls($handlingSubject, $handlingSubject, $this->orderPaymentInterfaceMock, $this->orderPaymentInterfaceMock);
//        $this->GMOCreditCard->handle($handlingSubject, $response);
//    }

    public function testHandleLocalizedException()
    {
        $handlingSubject = [
            'amount' => 1,
            'payment' => $this->orderMock,
            'creditmemo' => $this->orderPaymentInterfaceMock,
            'invoice' => $this->orderPaymentInterfaceMock,
            'transaction_id' => $this->orderPaymentInterfaceMock,

        ];
        $response = [
            'GATEWAY_REFUND_GATEWAY_URL' => 'http://example-refund-url.com',
            'GATEWAY_MERCHANT_ID' => '',
            'GATEWAY_API_KEY' => ''
        ];

        $this->orderMock->expects($this->any())->method('getPayment')->willReturn($this->orderPaymentInterfaceMock);
        $this->orderPaymentInterfaceMock->expects($this->any())->method('getData')
            ->withConsecutive(['creditmemo'], [], ['invoice'], ['transaction_id'])
            ->willReturnOnConsecutiveCalls('', $handlingSubject, $this->orderPaymentInterfaceMock, $this->orderPaymentInterfaceMock);

        $this->expectException(LocalizedException::class);
        $this->GMOCreditCardMock->handle($handlingSubject, $response);

    }


    public function testParseHeaders()
    {
        $headers = array('val1', 'val2');
        $expects = [
            0 => 'val1',
            1 => 'val2'
        ];

        $this->GMOCreditCardMock->parseHeaders($headers);
        $this->assertEquals($expects, $this->GMOCreditCardMock->parseHeaders($headers));
    }

    public function testParseHeadersAssociatifArray()
    {
        $headers = array(
            'key1:val1',
            'key2:val2'
        );
        $expects = [
            'key1' => 'val1',
            'key2' => 'val2'
        ];
        $this->GMOCreditCardMock->parseHeaders($headers);
        $this->assertEquals($expects, $this->GMOCreditCardMock->parseHeaders($headers));
    }


}

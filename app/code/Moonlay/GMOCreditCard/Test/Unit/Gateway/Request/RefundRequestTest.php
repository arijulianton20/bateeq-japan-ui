<?php

namespace Moonlay\GMOCreditCard\Test\Unit\Gateway\Request;

use Magento\Checkout\Model\Session;
use Moonlay\GMOCreditCard\Gateway\Config\Config;
use Psr\Log\LoggerInterface;
use Moonlay\GMOCreditCard\Gateway\Request\RefundRequest;
use PHPUnit\Framework\TestCase;

class RefundRequestTest extends TestCase
{
    private $gatewayConfig;
    private $logger;
    private $session;
    private $RefundRequestMock;

    protected function setUp()
    {
        $this->gatewayConfig = $this->getMockBuilder(\Moonlay\GMOCreditCard\Gateway\Config\Config::class)
            ->disableOriginalConstructor()
            ->setMethods(['getApiKey', 'getMerchantNumber'])
            ->getMock();

        $this->logger = $this->getMockBuilder(\Psr\Log\LoggerInterface::class)
            ->getMockForAbstractClass();

        $this->session = $this->getMockBuilder(\Magento\Checkout\Model\Session::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->RefundRequestMock = new \Moonlay\GMOCreditCard\Gateway\Request\RefundRequest(
            $this->gatewayConfig,
            $this->logger,
            $this->session
        );

    }

    public function testBuild()
    {
        $this->gatewayConfig->expects($this->any())->method('getApiKey')->willReturn('any_getway_api_key');
        $this->gatewayConfig->expects($this->any())->method('getMerchantNumber')->willReturn('any_merchant_id');

        $expect = ['GATEWAY_MERCHANT_ID' => 'any_merchant_id', 'GATEWAY_API_KEY' => 'any_getway_api_key', 'GATEWAY_REFUND_GATEWAY_URL' => 'https://portals.oxipay.jp/api/ExternalRefund/processrefund'];
        $this->assertEquals($expect, $this->RefundRequestMock->build([]));
    }

}

<?php

namespace Moonlay\GMOCreditCard\Test\Unit\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\Locale\ResolverInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use Magento\Payment\Helper\Data as PaymentData;
use Magento\Store\Model\StoreManagerInterface;
use Moonlay\GMOCreditCard\Gateway\Config\Config;
use Moonlay\GMOCreditCard\Helper\Data;
use PHPUnit\Framework\TestCase;

class DataTest extends TestCase
{
    private $objectManagerHelper;
    private $gatewayConfig;
    private $objectManager;
    private $context;
    private $paymentData;
    private $storeManager;
    private $localeResolver;
    private $dataMock;
    private $urlBuilderMock;
    private $scopeConfigMock;
    private $storeMock;

    protected function setUp()
    {
        $this->urlBuilderMock = $this->createMock(\Magento\Framework\UrlInterface::class);
        $this->scopeConfigMock = $this->createMock( \Magento\Framework\App\Config\ScopeConfigInterface::class);
        $this->gatewayConfig = $this->getMockBuilder(\Moonlay\GMOCreditCard\Gateway\Config\Config::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->storeMock=$this->createMock(\Magento\Store\Model\Store::class);

        $this->objectManager = $this->getMockBuilder(\Magento\Framework\ObjectManagerInterface::class)
            ->getMockForAbstractClass();
        $this->context = $this->getMockBuilder(\Magento\Framework\App\Helper\Context::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->context ->expects($this->any())->method('getUrlBuilder')->willReturn($this->urlBuilderMock);
        $this->context ->expects($this->any())->method('getScopeConfig')->willReturn($this->scopeConfigMock);

        $this->paymentData = $this->getMockBuilder(\Magento\Payment\Helper\Data ::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->storeManager = $this->getMockBuilder(\Magento\Store\Model\StoreManagerInterface::class)
            ->getMockForAbstractClass();
        $this->localeResolver = $this->getMockBuilder(\Magento\Framework\Locale\ResolverInterface::class)
            ->getMockForAbstractClass();

        $this->objectManagerHelper = new ObjectManagerHelper($this);
        $this->dataMock = $this->objectManagerHelper->getObject(
            Data::class,
            [
                'gatewayConfig' => $this->gatewayConfig,
                'objectManager' => $this->objectManager,
                'context' => $this->context,
                'paymentData' => $this->paymentData,
                'storeManager' => $this->storeManager,
                'localeResolver' => $this->localeResolver
            ]
        );


    }


    public function testGetInstance()
    {
        $this->dataMock->getInstance( $this->objectManager);
        $this->assertEquals(null,$this->dataMock->getInstance($this->objectManager));
    }

    public function testGetObjectManager(){
        $refClass = new \ReflectionClass(Data::class);
        $getObjectManager = $refClass->getMethod('getObjectManager');
        $getObjectManager->setAccessible(true);
        $this->assertInstanceOf(\Magento\Framework\ObjectManagerInterface::class,$getObjectManager->invoke($this->dataMock, []));

    }

    public function testGetStoreManager(){
        $refClass = new \ReflectionClass(Data::class);
        $getStoreManager = $refClass->getMethod('getStoreManager');
        $getStoreManager->setAccessible(true);
        $this->assertInstanceOf(\Magento\Store\Model\StoreManagerInterface::class,$getStoreManager->invoke($this->dataMock, []));
    }

    public function testGetUrlBuilder()
    {
        $this->dataMock->getUrlBuilder();
        $this->assertInstanceOf(\Magento\Framework\UrlInterface::class, $this->dataMock->getUrlBuilder());
    }

    public function testGetScopeConfig(){
        $refClass = new \ReflectionClass(Data::class);
        $getScopeConfig = $refClass->getMethod('getScopeConfig');
        $getScopeConfig->setAccessible(true);

        $this->assertInstanceOf(\Magento\Framework\App\Config\ScopeConfigInterface::class, $getScopeConfig->invoke($this->dataMock, []));
    }

    public function testGetLocaleResolver(){
        $refClass = new \ReflectionClass(Data::class);
        $getLocaleResolver = $refClass->getMethod('getLocaleResolver');
        $getLocaleResolver->setAccessible(true);
        $this->assertInstanceOf(\Magento\Framework\Locale\ResolverInterface ::class, $getLocaleResolver->invoke($this->dataMock, []));

    }
    public function testGetCheckoutUrl()
    {

        $this->gatewayConfig->expects($this->any())->method('getGatewayUrl')->willReturn('https://pt01.mul-pay.jp/link/tshop00041226/Multi/Entry%22)');
        $this->dataMock->getCheckoutUrl();

        $expected ='https://pt01.mul-pay.jp/link/tshop00041226/Credit/Entry%22)';
        $this->assertEquals($expected,$this->dataMock->getCheckoutUrl());
    }



    public function testGetCompleteUrl()
    {
        $this->urlBuilderMock->expects($this->any())->method('getUrl')->with('gmocreditcard/checkout/success')->willReturn('gmocreditcard/checkout/success');

        $expected ='gmocreditcard/checkout/success';
        $this->assertEquals($expected,$this->dataMock->getCompleteUrl());

    }
    public function testGetCancelledUrl()
    {
        $this->storeManager->expects($this->any())->method('getStore')->willReturn($this->storeMock);
        $this->storeMock->expects($this->any())->method('getBaseUrl')->willReturn('base_url/');
        $orderId = "1";
        $expected ="?orderId=$orderId";
        $this->assertEquals($expected,$this->dataMock->getCancelledUrl($orderId));
    }



    public function testGetStoreCode()
    {
        $this->storeManager->expects($this->any())->method('getStore')->willReturn($this->storeMock);
        $this->storeMock->expects($this->any())->method('getCode')->willReturn('some code');
        $this->dataMock-> getStoreCode();
        $expected='some code';
        $this->assertEquals($expected,$this->dataMock->getStoreCode());
    }




}

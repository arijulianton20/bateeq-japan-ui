<?php

namespace Moonlay\GMOCreditCard\Test\Unit\Model\Ui;

use Magento\Backend\Model\Session\Quote;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use Magento\Framework\View\Asset\Repository;
use Moonlay\GMOCreditCard\Gateway\Config\Config;
use Moonlay\GMOCreditCard\Model\Ui\ConfigProvider;
use PHPUnit\Framework\TestCase;

class ConfigProviderTest extends TestCase
{
    private $configMock;
    private $sessionMock;
    private $quoteMock;
    private $contextMock;
    private $repositoryMock;
    private $objectManagerHelper;
    private $configProviderMock;
    const KEY_GATEWAY_LOGO = 'gateway_logo';


    protected function setUp()
    {

        $this->configMock = $this->getMockBuilder(\Moonlay\GMOCreditCard\Gateway\Config\Config::class)
            ->disableOriginalConstructor()
            ->getMock();


        $this->sessionMock = $this->getMockBuilder(Session::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->quoteMock = $this->getMockBuilder(Quote::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->contextMock = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->repositoryMock = $this->getMockBuilder(Repository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->objectManagerHelper = new ObjectManagerHelper($this);
        $this->configProviderMock = $this->objectManagerHelper->getObject(
            \Moonlay\GMOCreditCard\Model\Ui\ConfigProvider::class,
            [

                'gatewayConfig' => $this->configMock,
                'customerSession' => $this->sessionMock,
                'sessionQuote' => $this->quoteMock,
                'context' => $this->contextMock,
                'assetRepo' => $this->repositoryMock
            ]
        );

    }

    public function testGetConfig()
    {
        $expected = [
            'payment' => [
                'gmo_multipayment' => [
                    'title' => '',
                    'description' => '',
                    'logo' => '',
                    'allowed_countries' => '',
                ]
            ]
        ];

        $this->assertArrayHasKey('payment', $this->configProviderMock->getConfig(), "Array doesn't contains 'payment' as key");
    }

    public function testGetConfig2()
    {

        $this->configMock->expects($this->once())->method('getLogo')->will($this->returnValue(self::KEY_GATEWAY_LOGO));

        $this->configProviderMock->getConfig();
    }

}

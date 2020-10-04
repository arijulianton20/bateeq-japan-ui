<?php

namespace Moonlay\GMOMultiPayment\Test\Unit\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use Moonlay\GMOMultiPayment\Setup\InstallData;
use PHPUnit\Framework\TestCase;

class InstallDataTest extends TestCase
{
    private $objectManagerHelper;
    private $InstallDataMock;
    private $moduleDataSetupInterfaceMock;
    private $moduleContextInterfaceMock;
    private $adapterInterfaceMock;

    protected function setUp()
    {
        $this->moduleContextInterfaceMock = $this->createMock(ModuleContextInterface::class);
        $this->moduleDataSetupInterfaceMock = $this->createMock(\Magento\Framework\Setup\ModuleDataSetupInterface::class);

        $this->adapterInterfaceMock =$this->createMock(\Magento\Framework\DB\Adapter\AdapterInterface::class);

        $this->moduleDataSetupInterfaceMock->expects($this->any())->method('getConnection')->willReturn($this->adapterInterfaceMock);
        $this->objectManagerHelper = new ObjectManagerHelper($this);
        $this->InstallDataMock = $this->objectManagerHelper->getObject(
            \Moonlay\GMOCreditCard\Setup\InstallData::class,
            [
                'ModuleDataSetupInterface' => $this->moduleDataSetupInterfaceMock,
                'ModuleContextInterface' => $this->moduleContextInterfaceMock
            ]
        );
    }

    public function testInstall()
    {
        $this->InstallDataMock->install($this->moduleDataSetupInterfaceMock,$this->moduleContextInterfaceMock);
    }
}

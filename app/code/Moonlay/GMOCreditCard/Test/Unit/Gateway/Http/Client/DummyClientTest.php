<?php

namespace Moonlay\GMOCreditCard\Test\Unit\Gateway\Http\Client;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use Moonlay\GMOCreditCard\Gateway\Http\Client\DummyClient;
use PHPUnit\Framework\TestCase;

class DummyClientTest extends TestCase
{
    private $transferInterfaceMock;
    private $dummyClientMock;
protected function setUp()
{
    $this->transferInterfaceMock=$this->createMock(\Magento\Payment\Gateway\Http\TransferInterface::class);
    ;
    $this->objectManagerHelper = new ObjectManagerHelper($this);
    $this->dummyClientMock = $this->objectManagerHelper->getObject(
        DummyClient::class,
        [

        ]
    );
}

    public function testPlaceRequest()
    {
        $this->dummyClientMock->placeRequest($this->transferInterfaceMock);
    }
}

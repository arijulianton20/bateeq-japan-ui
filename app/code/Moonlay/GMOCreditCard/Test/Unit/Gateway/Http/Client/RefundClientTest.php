<?php

namespace Moonlay\GMOCreditCard\Test\Unit\Gateway\Http\Client;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use Moonlay\GMOCreditCard\Gateway\Http\Client\RefundClient;
use Magento\Payment\Gateway\Http\TransferInterface;
use PHPUnit\Framework\TestCase;

class RefundClientTest extends TestCase
{
    private $objectManagerHelper;
    private $refundClientMock;
    private $transferInterfaceMock;
protected function setUp()
{
    $this->transferInterfaceMock=$this->createMock(\Magento\Payment\Gateway\Http\TransferInterface::class);
    $this->objectManagerHelper = new ObjectManagerHelper($this);
    $this->refundClientMock = $this->objectManagerHelper->getObject(
        RefundClient::class,
        [

        ]
    );
}

    public function testPlaceRequest()
    {
        $this->refundClientMock->placeRequest($this->transferInterfaceMock);
    }
}

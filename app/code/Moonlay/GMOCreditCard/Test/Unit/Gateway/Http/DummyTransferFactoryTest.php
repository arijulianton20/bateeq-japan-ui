<?php

namespace Moonlay\GMOCreditCard\Test\Unit\Gateway\Http;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use Magento\Payment\Gateway\Http\Transfer;
use Magento\Payment\Gateway\Http\TransferBuilder;
use Moonlay\GMOCreditCard\Gateway\Http\DummyTransferFactory;
use PHPUnit\Framework\TestCase;

class DummyTransferFactoryTest extends TestCase
{
    private $transferBuilderMock;
    private $objectManagerHelper;
    private $dummyTransferFactoryMock;

    protected function setUp()
    {
        $this->transferBuilderMock = $this->getMockBuilder(\Magento\Payment\Gateway\Http\TransferBuilder::class)
            ->setMethods(['setBody', 'setMethod', 'build'])
            ->getMock();

        $this->objectManagerHelper = new ObjectManagerHelper($this);
        $this->dummyTransferFactoryMock = $this->objectManagerHelper->getObject(
            DummyTransferFactory::class,
            [
                'transferBuilder' => $this->transferBuilderMock
            ]
        );
    }

    public function testCreate()
    {
        $clientConfig = ['config'];
        $headers = ['Header'];
        $body = ['data', 'data2'];
        $auth = ['username', 'password'];
        $method = 'POST';
        $uri = 'https://gateway.com';
        $encode = false;

        $expect = [
            $clientConfig,
            $headers,
            $body,
            $auth,
            $method,
            $uri,
            $encode
        ];


        $this->transferBuilderMock->expects($this->any())
            ->method('setBody')
            ->with(['data', 'data2'])
            ->willReturnSelf();
        $this->transferBuilderMock->expects($this->any())
            ->method('setMethod')
            ->with('POST')
            ->willReturnSelf();

        $this->transferBuilderMock->expects($this->any())
            ->method('build')
            ->willReturn(
                [
                    $clientConfig,
                    $headers,
                    $body,
                    $auth,
                    $method,
                    $uri,
                    $encode
                ]
            );

        $this->assertEquals($expect, $this->dummyTransferFactoryMock->create(['data', 'data2']));
    }


}

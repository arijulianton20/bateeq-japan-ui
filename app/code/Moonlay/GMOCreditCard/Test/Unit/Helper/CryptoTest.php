<?php

namespace Moonlay\GMOCreditCard\Test\Unit\Helper;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use Moonlay\GMOCreditCard\Helper\Crypto;
use PHPUnit\Framework\TestCase;

class CryptoTest extends TestCase
{
    private $objectManagerHelper;
    private $cryptoMock;

    protected function setUp()
    {
        $this->objectManagerHelper = new ObjectManagerHelper($this);
        $this->cryptoMock = $this->objectManagerHelper->getObject(
            Crypto::class,
            [

            ]
        );
    }

    public function testGetEncriptPassString()
    {
        $response = [
            'ShopID' => 'any shop ID'
        ];
        $shop_id = 'any shop ID';

        $this->assertEquals(true, $this->cryptoMock->isValidSignature($response, $shop_id));
    }

    public function testIsValidSignature()
    {
        $array = array('any', 'text', 'will encrypted');
        $expect = '0eb7ad2e89881dca64ccb95f35c368e0';
        $this->assertEquals($expect, $this->cryptoMock->getEncriptPassString($array));
    }
}

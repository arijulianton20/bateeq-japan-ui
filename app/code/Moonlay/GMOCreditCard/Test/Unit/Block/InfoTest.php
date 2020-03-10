<?php

namespace Moonlay\GMOCreditCard\Test\Unit\Block;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use Moonlay\GMOCreditCard\Block\Info;
use PHPUnit\Framework\TestCase;

class InfoTest extends TestCase
{
private $infoMock;
private $objectManagerHelperMock;
    protected function setUp()
    {

        $this->objectManagerHelper = new ObjectManagerHelper($this);
        $this->infoMock = $this->objectManagerHelper->getObject(
            \Moonlay\GMOCreditCard\Block\Info::class,
            [

            ]
        );

    }

    public function testGetLabel(){
        $label='any label';

        $refClass = new \ReflectionClass(\Moonlay\GMOCreditCard\Block\Info::class);
        $getLabel = $refClass->getMethod('getLabel');
        $getLabel->setAccessible(true);
        $this->assertEquals($label, $getLabel->invoke($this->infoMock, $label));

    }
}

<?php

namespace Moonlay\GMOCreditCard\Test\Unit\Block\System\Config\Form\Field;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use Moonlay\GMOCreditCard\Block\System\Config\Form\Field\Image;

use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{
    private $AbstractElementMock;
    private $ImageObj;

    protected function setUp()
    {
        $this->AbstractElementMock = $this->createMock(\Magento\Framework\Data\Form\Element\AbstractElement::class);

        $this->objectManagerHelper = new ObjectManagerHelper($this);
        $this->ImageObj = $this->objectManagerHelper->getObject(
            Image::class,
            [

            ]
        );
    }

    public function test_getElementHtml()
    {
        $html='<img src="" alt="Credit Card logo" height="50" width="85" class="small-image-preview v-middle" /><p class="note"><span>Upload a new image if you wish to replace this logo.</span></p>';
        $refClass = new \ReflectionClass(Image::class);
        $_getElementHtml = $refClass->getMethod('_getElementHtml');
        $_getElementHtml->setAccessible(true);
        $result = $_getElementHtml->invoke($this->ImageObj, $this->AbstractElementMock);
        $this->assertEquals($html, $result);
    }


}

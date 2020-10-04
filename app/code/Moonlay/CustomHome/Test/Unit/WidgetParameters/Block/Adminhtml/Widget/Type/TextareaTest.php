<?php

namespace Moonlay\CustomHome\Test\Unit\WidgetParameters\Block\Adminhtml\Widget\Type;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use Moonlay\CustomHome\WidgetParameters\Block\Adminhtml\Widget\Type\Textarea;
use PHPUnit\Framework\TestCase;

class TextareaTest extends TestCase
{
private $textareaObj;
private $objectManagerHelper;
private $contextMock;
private $elementFactoryMock;
private $elementMock;
private $inputMock;


    protected function setUp()
    {
        $this->inputMock = $this->getMockBuilder(\Magento\Framework\Data\Form\Element\Text::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->elementMock=$this->getMockBuilder(\Magento\Framework\Data\Form\Element\AbstractElement::class)
            ->disableOriginalConstructor()
            ->setMethods(['getRequired'])
            ->getMockForAbstractClass();

        $this->contextMock=$this->getMockBuilder(\Magento\Backend\Block\Template\Context::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->elementFactoryMock=$this->getMockBuilder(\Magento\Framework\Data\Form\Element\Factory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->objectManagerHelper = new ObjectManagerHelper($this);
        $this->textareaObj = $this->objectManagerHelper->getObject(
            Textarea::class,
            [
                'context'=>$this->contextMock,
                'elementFactory'=>$this->elementFactoryMock,
                'data' => []
            ]
        );

    }


    public function testPrepareElementHtml()
    {
        $data=['data' => []];
        $this->elementFactoryMock->expects($this->any())
        ->method('create')
        ->with("textarea",$data)
        ->willReturn($this->inputMock);

        $this->elementMock->expects($this->any())
            ->method('getRequired')
            ->willReturn(true);

        $result =  $this->textareaObj->prepareElementHtml($this->elementMock);
        $this->assertInstanceOf(\Magento\Framework\Data\Form\Element\AbstractElement::class, $result);
    }
}

<?php

namespace Moonlay\CustomHome\Test\Unit\Block\Widget;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use Moonlay\CustomHome\Block\Widget\TextArea;
use PHPUnit\Framework\TestCase;

class TextAreaTest extends TestCase
{
    private $objectManagerHelper;
    private $textAreaObj;
    private $contextMock;
    private $elementFactoryMock;
    private $elementMock;
private $dataForm;


    protected function setUp()
    {

        $this->contextMock = $this->getMockBuilder(\Magento\Backend\Block\Template\Context::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->dataForm = $this->getMockBuilder(\Magento\Framework\Data\Form::class)
            ->disableOriginalConstructor()
            ->getMock();


        $this->elementFactoryMock = $this->getMockBuilder(\Magento\Framework\Data\Form\Element\Factory::class)
            ->disableOriginalConstructor()
            ->getMock();


        $this->elementMock =  $this->getMockBuilder(\Magento\Framework\Data\Form\Element\AbstractElement::class)
            ->disableOriginalConstructor()
            ->setMethods(['getElementHtml','getRequired'])
            ->getMockForAbstractClass();


        $this->objectManagerHelper = new ObjectManagerHelper($this);
        $this->textAreaObj = $this->objectManagerHelper->getObject(
            TextArea::class,
            [
                'context'=>$this->contextMock,
                'elementFactory'=>$this->elementFactoryMock,
                'data'=>[],

            ]
        );
    }

    public function testPrepareElementHtml()
    {
        $this->elementFactoryMock->expects($this->any())
            ->method('create')
            ->with("textarea",[
                'data' => []
            ])
            ->willReturn($this->elementMock);
        $this->elementMock->expects($this->any())->method('getRequired')->willReturn(true);
        $this->elementMock->expects($this->any())->method('getElementHtml')->willReturn('anyelement');

        $this->assertInstanceOf(\Magento\Framework\Data\Form\Element\AbstractElement::class, $this->textAreaObj->prepareElementHtml($this->elementMock));
    }


}

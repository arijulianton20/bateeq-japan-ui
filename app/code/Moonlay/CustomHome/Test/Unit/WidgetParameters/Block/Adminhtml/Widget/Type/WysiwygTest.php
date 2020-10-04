<?php

namespace Moonlay\CustomHome\Test\Unit\WidgetParameters\Block\Adminhtml\Widget\Type;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use Moonlay\CustomHome\WidgetParameters\Block\Adminhtml\Widget\Type\Wysiwyg;
use PHPUnit\Framework\TestCase;

class WysiwygTest extends TestCase
{
    private $contextMock;
    private $objectManagerHelper;
    private $wysiwygObj;
    private $factoryElementMock;
    private $wysiwygConfigMock;
    private $elementMock;
    private $inputMock;
    private $dataForm;

    private $escaperMock;
private $factoryCollectionMock;


    

    protected function setUp()
    {
        $this->factoryElementMock=$this->getMockBuilder(\Magento\Framework\Data\Form\Element\Factory::class)
        ->disableOriginalConstructor()
        ->getMock();

        $this->factoryCollectionMock = $this->getMockBuilder(\Magento\Framework\Data\Form\Element\CollectionFactory::class)
        ->disableOriginalConstructor()
        ->getMock();

        $this->escaperMock = $this->getMockBuilder(\Magento\Framework\Escaper::class)
        ->disableOriginalConstructor()
        ->getMock();

        $this->inputMock = $this->getMockBuilder(\Magento\Framework\Data\Form\Element\Text::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->elementMock=$this->getMockBuilder(\Magento\Framework\Data\Form\Element\AbstractElement::class)
            ->setConstructorArgs([ $this->factoryElementMock,$this->factoryCollectionMock, $this->escaperMock , []])
            ->setMethods(['getForm','getRequired'])
            ->getMockForAbstractClass();

        $this->contextMock=$this->getMockBuilder(\Magento\Backend\Block\Template\Context::class)
            ->disableOriginalConstructor()
            ->getMock();

        

        $this->wysiwygConfigMock=$this->getMockBuilder(\Magento\Cms\Model\Wysiwyg\Config::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->dataForm=$this->getMockBuilder(\Magento\Framework\Data\Form::class)
            ->disableOriginalConstructor()
            ->getMock();


        $this->objectManagerHelper = new ObjectManagerHelper($this);
        $this->wysiwygObj = $this->objectManagerHelper->getObject(
            Wysiwyg::class,
            [
                'context'=>$this->contextMock,
                'factoryElement'=>$this->factoryElementMock,
                'wysiwygConfig'=>$this->wysiwygConfigMock,
                'data' => []
            ]
        );
    }


    public function testPrepareElementHtml()
    {
        $data=['data' => []];
        $this->factoryElementMock->expects($this->any())
            ->method('create')
            ->with("editor",$data)
            ->willReturn($this->elementMock);

        $this->elementMock->expects($this->any())
            ->method('getRequired')
            ->willReturn(true);

        $this->elementMock->expects($this->any())
            ->method('getForm')
            ->willReturn($this->dataForm);

        $result =  $this->wysiwygObj->prepareElementHtml($this->elementMock);
        $this->assertInstanceOf(\Magento\Framework\Data\Form\Element\AbstractElement::class, $result);
    }


}

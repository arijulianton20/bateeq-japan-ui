<?php

namespace Moonlay\CustomerAttribute\Test\Unit\Block\Adminhtml\Attribute\Edit\Tab;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use Moonlay\CustomerAttribute\Block\Adminhtml\Attribute\Edit\Tab\Main;

class MainTest extends \PHPUnit\Framework\TestCase
{
    private $contextMock;
    private $registryMock;
    private $formFactoryMock;
    private $eavDataMock;
    private $yesnoFactoryMock;
    private $inputTypeFactoryMock;
    private $propertyLockerMock;
    private $mainObj;
    private $dataForm;
    private $attributeMock;
    private $fieldsetMock;
    private $localeDateMock;
    private $typeMock;
    private $urlBuilderMock;
    private $collectionMock;
    private $elementMock;


    protected function setUp()
    {
        $this->elementMock= $this->getMockBuilder(\Magento\Framework\Data\Form\Element\AbstractElement::class)
            ->disableOriginalConstructor()
           ->setMethods(['getId'])
            ->getMockForAbstractClass();


        $this->collectionMock= $this->getMockBuilder(\Magento\Framework\Data\Form\Element\Collection::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->urlBuilderMock= $this->getMockBuilder(\Magento\Framework\UrlInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->typeMock = $this->getMockBuilder(\Magento\Eav\Model\Entity\Type::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->localeDateMock = $this->getMockBuilder(\Magento\Framework\Stdlib\DateTime\TimezoneInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->dataForm = $this->getMockBuilder(\Magento\Framework\Data\Form::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->fieldsetMock = $this->getMockBuilder(\Magento\Framework\Data\Form\Element\Fieldset::class)
            ->disableOriginalConstructor()
            ->getMock();


        $this->attributeMock = $this->getMockBuilder(\Magento\Catalog\Model\ResourceModel\Eav\Attribute::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->contextMock = $this->getMockBuilder(\Magento\Backend\Block\Template\Context::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->contextMock->expects($this->any())->method('getLocaleDate')->willReturn($this->localeDateMock);
        $this->contextMock->expects($this->any())->method('getUrlBuilder')->willReturn($this->urlBuilderMock);


        $this->registryMock = $this->getMockBuilder(\Magento\Framework\Registry::class)
            ->disableOriginalConstructor()
            ->getMock();


        $this->formFactoryMock = $this->getMockBuilder(\Magento\Framework\Data\FormFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->eavDataMock = $this->getMockBuilder(\Magento\Eav\Helper\Data::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->yesnoFactoryMock = $this->getMockBuilder(\Magento\Config\Model\Config\Source\YesnoFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create', 'toOptionArray'])
            ->getMock();

        $this->inputTypeFactoryMock = $this->getMockBuilder(\Magento\Eav\Model\Adminhtml\System\Config\Source\InputtypeFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create', 'toOptionArray'])
            ->getMock();

        $this->propertyLockerMock = $this->getMockBuilder(\Magento\Eav\Block\Adminhtml\Attribute\PropertyLocker::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->objectManagerHelper = new ObjectManagerHelper($this);
        $this->mainObj = $this->objectManagerHelper->getObject(
            \Moonlay\CustomerAttribute\Block\Adminhtml\Attribute\Edit\Tab\Main::class,
            [
                'context' => $this->contextMock,
                'registry' => $this->registryMock,
                'formFactory' => $this->formFactoryMock,
                'eavData' => $this->eavDataMock,
                'yesnoFactory' => $this->yesnoFactoryMock,
                'inputTypeFactory' => $this->inputTypeFactoryMock,
                'propertyLocker' => $this->propertyLockerMock,
                'data' => []
            ]
        );
    }


    public function test_prepareForm()
    {
        $this->registryMock->expects($this->any())->method('registry')->with('entity_attribute')->willReturn($this->attributeMock);

        $data = ['data' => ['id' => 'edit_form', 'action' => '', 'method' => 'post']];
        $this->formFactoryMock->expects($this->any())->method('create')->with($data)->willReturn($this->dataForm);

        $this->dataForm->expects($this->any())->method('addFieldset')->with('base_fieldset', ['legend' => __('Attribute Properties')])->willReturn($this->fieldsetMock);

        $this->attributeMock->expects($this->any())->method('getAttributeId')->willReturn(true);
        $this->yesnoFactoryMock->expects($this->any())->method('create')->willReturnSelf();
        $this->yesnoFactoryMock->expects($this->any())->method('toOptionArray')->willReturnSelf();

        $this->inputTypeFactoryMock->expects($this->any())->method('create')->willReturnSelf();
        $this->inputTypeFactoryMock->expects($this->any())->method('toOptionArray')->willReturnSelf();

        $this->attributeMock->expects($this->any())->method('getEntityType')->willReturn($this->typeMock);

        $this->urlBuilderMock->expects($this->any())->method('getBaseUrl')->willReturn('url_base');

        $this->dataForm->expects($this->any())
            ->method('getElement')
            ->withConsecutive(['base_fieldset'],['frontend_input'])
            ->willReturn($this->fieldsetMock);

        $this->fieldsetMock->expects($this->any())->method('getElements')->willReturn($this->collectionMock);

        $this->collectionMock->expects($this->once())
            ->method('getIterator')
            ->willReturn(new \ArrayObject([$this->elementMock]));

        $refClass = new \ReflectionClass(Main::class);
        $_prepareForm = $refClass->getMethod('_prepareForm');
        $_prepareForm->setAccessible(true);

        $result = $_prepareForm->invoke($this->mainObj, []);
        $this->assertInstanceOf(\Moonlay\CustomerAttribute\Block\Adminhtml\Attribute\Edit\Tab\Main::class, $result);

    }

}
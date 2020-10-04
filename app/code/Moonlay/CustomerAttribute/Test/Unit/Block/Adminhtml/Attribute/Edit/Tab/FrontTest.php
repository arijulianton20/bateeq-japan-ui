<?php

namespace Moonlay\CustomerAttribute\Test\Unit\Block\Adminhtml\Attribute\Edit\Tab;

use Magento\Catalog\Model\Entity\Attribute;
use Moonlay\CustomerAttribute\Block\Adminhtml\Attribute\Edit\Tab\Front;

class FrontTest extends \PHPUnit\Framework\TestCase
{

    private $attributeMock;
    /**
     * @var Yesno
     */
    private $yesNoMock;

    /**
     * @var PropertyLocker
     */
    private $propertyLockerMock;

    /**
     * @var PropertyLocker
     */
    private $contextMock;

    /**
     * @var PropertyLocker
     */
    private $registryMock;
    private $requestMock;
    /**
     * @var PropertyLocker
     */
    private $formFactoryMock;

    /**
     * @var PropertyLocker
     */
    private $WidgetFormMock;
    private $dataFormMock;
    private $FieldsetMock;
    private $UrlInterfaceMock;
    private $objectManagerMock;

    protected function setUp()
    {

        $this->attributeMock = $this->getMockBuilder(\Magento\Eav\Model\Entity\Attribute::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->requestMock = $this->getMockBuilder(\Magento\Framework\App\RequestInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['has'])
            ->getMockForAbstractClass();

        $this->UrlInterfaceMock = $this->getMockBuilder(\Magento\Framework\UrlInterface::class)
            ->getMock();

        $this->objectManagerMock = $this->getMockBuilder(\Magento\Framework\ObjectManagerInterface::class)
            ->getMock();

        $this->contextMock = $this->createMock(\Magento\Backend\Block\Template\Context::class);
        $this->registryMock = $this->getMockBuilder(\Magento\Framework\Registry::class)
            ->getMock();
        $instanceName = \Magento\Framework\Data\Form::class;

        $this->formFactoryMock = $this->getMockBuilder(\Magento\Framework\Data\FormFactory::class)
            ->setConstructorArgs([$this->objectManagerMock, $instanceName])
            ->getMock();

        $this->FieldsetMock = $this->getMockBuilder(\Magento\Framework\Data\Form\Element\Fieldset::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->yesNoMock = $this->createMock(\Magento\Config\Model\Config\Source\Yesno::class);
        $this->propertyLockerMock = $this->createMock(\Magento\Eav\Block\Adminhtml\Attribute\PropertyLocker::class);

        $this->dataFormMock = $this->getMockBuilder(\Magento\Framework\Data\Form::class)
            ->disableOriginalConstructor()
            ->getMock();


        $this->contextMock->expects($this->any())->method('getRequest')->willReturn($this->requestMock);
        $this->contextMock->expects($this->any())->method('getUrlBuilder')->willReturn($this->UrlInterfaceMock);

        $objectManagerHelper = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->FrontObj = $objectManagerHelper->getObject(\Moonlay\CustomerAttribute\Block\Adminhtml\Attribute\Edit\Tab\Front::class,
            [
                'context' => $this->contextMock,
                'registry' => $this->registryMock,
                'formFactory' => $this->formFactoryMock,
                'yesNo' => $this->yesNoMock,
                'propertyLocker' => $this->propertyLockerMock,
                'data' => [],

            ]);
    }

    public function test_prepareForm()
    {
        $refClass = new \ReflectionClass(Front::class);
        $_prepareForm = $refClass->getMethod('_prepareForm');
        $_prepareForm->setAccessible(true);

        $this->registryMock->expects($this->any())
            ->method('registry')
            ->with('entity_attribute')
            ->willReturn($this->attributeMock);

        $param = ['data' => ['id' => 'edit_form', 'action' => $this->FrontObj->getData('action'), 'method' => 'post']];

        $this->formFactoryMock->expects($this->any())
            ->method('create')
            ->with($param)
            ->willReturn($this->dataFormMock);

        $this->requestMock->expects($this->any())
            ->method('has')
            ->with('popup')
            ->willReturn(true);

        $this->dataFormMock->expects($this->any())
            ->method('addFieldset')
            ->with('front_fieldset', ['legend' => __('Storefront Properties'), 'collapsable' => $this->FrontObj->getRequest()->has('popup')])
            ->willReturn($this->FieldsetMock);

        $this->UrlInterfaceMock->expects($this->any())
            ->method('getBaseUrl')
            ->with([])
            ->willReturn('anyBaseUrl');


        $this->assertInstanceOf(\Magento\Backend\Block\Widget\Form::class, $_prepareForm->invoke($this->FrontObj, []));

    }


    public function testGetAttributeObject()
    {
        $refClass = new \ReflectionClass(Front::class);
        $getAttributeObject = $refClass->getMethod('getAttributeObject');
        $getAttributeObject->setAccessible(true);

        $this->registryMock->expects($this->any())
            ->method('registry')
            ->with('entity_attribute')
            ->willReturn($this->attributeMock);
        $this->FrontObj->getAttributeObject();
        $getAttributeObject->invoke($this->FrontObj);

        $this->assertInstanceOf(\Magento\Eav\Model\Entity\Attribute::class, $getAttributeObject->invoke($this->FrontObj));
    }


    public function test_initFormValues()
    {
        $refClass = new \ReflectionClass(Front::class);
        $_initFormValues = $refClass->getMethod('_initFormValues');
        $_initFormValues->setAccessible(true);

//        $this->registryMock->expects($this->any())
//            ->method('registry')
//            ->with('entity_attribute')
//            ->willReturn($this->AttributeMock);
//
//        $this->AttributeMock->expects($this->any())
//            ->method('getData')
//            ->willReturn([
//                'element1' =>'element1'
//            ]);

//        $this->dataFormMock->expects($this->any())
//            ->method('addValues')
//            ->with([])
//            ->willReturnSelf();

//      $_initFormValues->invoke($this->FrontObj, []);
        //    $this->assertEquals(true, $_initFormValues->invoke($this->FrontObj, ['Moonlay_CustomerAttribute::attribute_delete']));
    }

}

?>
<?php

namespace Moonlay\CustomerAttribute\Test\Unit\Block\Adminhtml\Attribute\Edit\Tab;

use Magento\Config\Model\Config\Source\Yesno;
use Magento\Eav\Block\Adminhtml\Attribute\PropertyLocker;
use Magento\Eav\Helper\Data;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use Moonlay\CustomerAttribute\Block\Adminhtml\Attribute\Edit\Tab\Advanced;

class AdvancedTest extends \PHPUnit\Framework\TestCase
{
    private $contextMock;
    private $registryMock;
    private $formFactoryMock;
    private $yesNoMock;
    private $eavDataMock;
    private $dataFormMock;
    private $objectManagerMock;
    private $fieldsetMock;
    private $localeDateMock;
    private $urlInterfaceMock;
    private $attributeMock;
    private $propertyLockerMock;
    private $filesystem;
    private $advancedObj;


    protected function setUp()
    {
        $this->filesystem = $this->createMock(\Magento\Framework\Filesystem::class);
        $this->fieldsetMock = $this->getMockBuilder(\Magento\Framework\Data\Form\Element\Fieldset::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->attributeMock = $this->createPartialMock(
            \Magento\Eav\Model\Entity\Attribute::class,
            ['getDefaultValue', 'setDisabled', 'getId', 'getEntityType', 'getIsUserDefined', 'getAttributeCode', 'getEntityTypeCode']
        );

        $this->localeDateMock = $this->getMockBuilder(\Magento\Framework\Stdlib\DateTime\TimezoneInterface::class)
            ->getMock();

        $this->urlInterfaceMock = $this->getMockBuilder(\Magento\Framework\UrlInterface::class)
            ->getMock();

        $this->objectManagerMock = $this->createMock(\Magento\Framework\ObjectManagerInterface::class);
        $this->dataFormMock = $this->createMock(\Magento\Framework\Data\Form::class);

        $this->contextMock = $this->getMockBuilder(\Magento\Backend\Block\Template\Context::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->registryMock = $this->getMockBuilder(\Magento\Framework\Registry::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->formFactoryMock = $this->getMockBuilder(\Magento\Framework\Data\FormFactory::class)
            ->setConstructorArgs([$this->objectManagerMock, $this->dataFormMock])
            ->getMock();

        $this->yesNoMock = $this->getMockBuilder(Yesno::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->eavDataMock = $this->getMockBuilder(Data::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->contextMock->expects($this->any())->method('getLocaleDate')->willReturn($this->localeDateMock);
        $this->contextMock->expects($this->any())->method('getUrlBuilder')->willReturn($this->urlInterfaceMock);
        $this->formFactoryMock->expects($this->any())->method('create')->willReturn($this->dataFormMock);
        $this->propertyLockerMock = $this->createMock(PropertyLocker::class);


    }


    public function test_initFormValues()
    {
        $refClass = new \ReflectionClass(Advanced::class);
        $_initFormValues = $refClass->getMethod('_initFormValues');
        $_initFormValues->setAccessible(true);

        //$this->registryMock->method('registry')->with('entity_attribute')->willReturn($this->attributeMock);

        // $this->dataFormMock->expects($this->any())->method('addValues')->with([])->willReturnSelf();
        //$_initFormValues->invoke($this->advancedObj);
    }

    public function testGetPropertyLockerNull()
    {

        $refClass = new \ReflectionClass(Advanced::class);
        $getPropertyLocker = $refClass->getMethod('getPropertyLocker');
        $getPropertyLocker->setAccessible(true);

        $objectManagerHelper = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->advancedObj = $objectManagerHelper->getObject(Advanced::class,
            [
                'context' => $this->contextMock,
                'registry' => $this->registryMock,
                'formFactory' => $this->formFactoryMock,
                'yesNo' => $this->yesNoMock,
                'eavData' => $this->eavDataMock,
                'disableScopeChangeList' => ['sku'],
                'data' => [],
            ]);
        $getPropertyLocker->invoke($this->advancedObj);

    }

    public function test_prepareForm()
    {
        $propertyLockerMock = $this->createMock(PropertyLocker::class);
        $objectManagerHelper = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->advancedObj = $objectManagerHelper->getObject(Advanced::class,
            [
                'context' => $this->contextMock,
                'registry' => $this->registryMock,
                'formFactory' => $this->formFactoryMock,
                'yesNo' => $this->yesNoMock,
                'eavData' => $this->eavDataMock,
                'disableScopeChangeList' => ['sku'],
                'data' => [],
                'filesystem' => $this->filesystem,
               'propertyLocker' => $propertyLockerMock,
            ]);
        $refClass = new \ReflectionClass(Advanced::class);
        $_prepareForm = $refClass->getMethod('_prepareForm');
        $_prepareForm->setAccessible(true);

        $this->registryMock->expects($this->any())
            ->method('registry')
            ->withConsecutive(['entity_attribute'], ['entity_attribute'])
            ->willReturnOnConsecutiveCalls($this->attributeMock, $this->attributeMock);

        $param = ['data' => ['id' => 'edit_form', 'action' => $this->advancedObj->getData('action'), 'method' => 'post']];

        $this->formFactoryMock->expects($this->any())
            ->method('create')
            ->with($param)
            ->willReturn($this->dataFormMock);

        $this->dataFormMock->expects($this->any())
            ->method('addFieldset')
            ->with('advanced_fieldset', ['legend' => __('Advanced Attribute Properties'), 'collapsable' => true])
            ->willReturn($this->fieldsetMock);

        $this->localeDateMock->expects($this->any())->method('getDateFormat')->with(\IntlDateFormatter::SHORT)->willReturn('YYYY/MM/DD');

        $this->attributeMock->expects($this->any())->method('getEntityType')->willReturnSelf();
        $this->attributeMock->expects($this->any())->method('getEntityTypeCode')->willReturn('costumer');
        $this->attributeMock->expects($this->any())->method('getId')->willReturn(true);

        $this->dataFormMock->expects($this->any())
            ->method('getElement')
            ->willReturnSelf();

        $this->urlInterfaceMock->expects($this->any())
            ->method('getBaseUrl')
            ->with([])
            ->willReturn('anyBaseUrl');
        $this->assertInstanceOf(Advanced::class, $_prepareForm->invoke($this->advancedObj, []));

    }





}
<?php

namespace Moonlay\CustomerAttribute\Test\Unit\Block\Adminhtml\Attribute\Edit;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use Moonlay\CustomerAttribute\Block\Adminhtml\Attribute\Edit;

class EditTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var instance object
     */
    private $contextMock;

    /**
     * @var instance object
     */
    private $EditMock;

    /**
     * @var instance object
     */
    private $_blockGroup;

    /**
     * @var instance object
     */
    private $registryMock;
    private $UrlInterfaceMock;
    private $ButtonListMock;
    private $RequestInterfaceMock;
    private $AuthorizationInterfaceMock;
    private $EscaperMock;

    protected function setUp()
    {
        $this->contextMock = $this->createMock(\Magento\Backend\Block\Widget\Context::class);

        $this->registryMock = $this->getMockBuilder(\Magento\Framework\Registry::class)
            ->setMethods(['registry', 'getIsUserDefined', 'getId'])
            ->getMock();

        $this->UrlInterfaceMock = $this->createMock(\Magento\Framework\UrlInterface::class);
        $this->ButtonListMock = $this->createMock(\Magento\Backend\Block\Widget\Button\ButtonList::class);
        $this->RequestInterfaceMock = $this->createMock(\Magento\Framework\App\RequestInterface::class);
        $this->AuthorizationInterfaceMock = $this->createMock(\Magento\Framework\AuthorizationInterface::class);

    }

    protected function tearDown()
    {
        $this->registryMock = null;
    }

    public function testUpdateButtonlist()
    {

        $this->registryMock->expects($this->any())->method('registry')
            ->with('entity_attribute')
            ->willReturnSelf();
        $this->registryMock->expects($this->any())->method('getIsUserDefined')->willReturn(true);
        $this->registryMock->expects($this->any())->method('getId')->willReturn(true);

        $this->AuthorizationInterfaceMock->expects($this->any())->method('isAllowed')->willReturn(true);

        $this->contextMock->expects($this->any())->method('getUrlBuilder')->willReturn($this->UrlInterfaceMock);
        $this->contextMock->expects($this->any())->method('getButtonList')->willReturn($this->ButtonListMock);
        $this->contextMock->expects($this->any())->method('getRequest')->willReturn($this->RequestInterfaceMock);
        $this->contextMock->expects($this->any())->method('getAuthorization')->willReturn($this->AuthorizationInterfaceMock);

        $this->objectManagerHelper = new ObjectManagerHelper($this);
        $this->EditMock = $this->objectManagerHelper->getObject(
            \Moonlay\CustomerAttribute\Block\Adminhtml\Attribute\Edit::class,
            [
                'context' => $this->contextMock,
                'registry' => $this->registryMock,
                'data' => []
            ]
        );
    }

    public function testRemoveButtonlist()
    {
        $this->registryMock->expects($this->any())->method('registry')
            ->with('entity_attribute')
            ->willReturnSelf();
        $this->registryMock->expects($this->any())->method('getIsUserDefined')->willReturn(false);
        $this->registryMock->expects($this->any())->method('getId')->willReturn(true);

        $this->AuthorizationInterfaceMock->expects($this->any())->method('isAllowed')->willReturn(true);

        $this->contextMock->expects($this->any())->method('getUrlBuilder')->willReturn($this->UrlInterfaceMock);
        $this->contextMock->expects($this->any())->method('getButtonList')->willReturn($this->ButtonListMock);
        $this->contextMock->expects($this->any())->method('getRequest')->willReturn($this->RequestInterfaceMock);
        $this->contextMock->expects($this->any())->method('getAuthorization')->willReturn($this->AuthorizationInterfaceMock);

        $this->objectManagerHelper = new ObjectManagerHelper($this);
        $this->EditMock = $this->objectManagerHelper->getObject(
            \Moonlay\CustomerAttribute\Block\Adminhtml\Attribute\Edit::class,
            [
                'context' => $this->contextMock,
                'registry' => $this->registryMock,
                'data' => []
            ]
        );
    }

    public function testNotAllowedAction()
    {
        $this->registryMock->expects($this->any())->method('registry')
            ->with('entity_attribute')
            ->willReturnSelf();
        $this->registryMock->expects($this->any())->method('getIsUserDefined')->willReturn(false);
        $this->registryMock->expects($this->any())->method('getId')->willReturn(false);

        $this->AuthorizationInterfaceMock->expects($this->any())->method('isAllowed')->willReturn(false);

        $this->contextMock->expects($this->any())->method('getUrlBuilder')->willReturn($this->UrlInterfaceMock);
        $this->contextMock->expects($this->any())->method('getButtonList')->willReturn($this->ButtonListMock);
        $this->contextMock->expects($this->any())->method('getRequest')->willReturn($this->RequestInterfaceMock);
        $this->contextMock->expects($this->any())->method('getAuthorization')->willReturn($this->AuthorizationInterfaceMock);

        $this->objectManagerHelper = new ObjectManagerHelper($this);
        $this->EditMock = $this->objectManagerHelper->getObject(
            \Moonlay\CustomerAttribute\Block\Adminhtml\Attribute\Edit::class,
            [
                'context' => $this->contextMock,
                'registry' => $this->registryMock,
                'data' => []
            ]
        );
    }

    public function testGetHeaderTextNewAttribute()
    {

        $this->EscaperMock = $this->getMockBuilder(\Magento\Framework\Escaper::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->registryMock = $this->getMockBuilder(\Magento\Framework\Registry::class)
            ->setMethods(['registry', 'getIsUserDefined', 'getId', 'getFrontendLabel'])
            ->getMock();


        $this->registryMock->expects($this->any())
            ->method('registry')
            ->withConsecutive(
                ['entity_attribute'],
                ['customer_attribute']
            )
            ->willReturnSelf();

        $this->registryMock->expects($this->any())->method('getFrontendLabel')
            ->willReturn(['"something string"']);


        $this->registryMock->expects($this->any())->method('getId')->willReturn(false);

        $this->EscaperMock->expects($this->any())
            ->method('escapeHtml')
            ->with('"something string"')
            ->willReturn("something string");

        $this->AuthorizationInterfaceMock->expects($this->any())->method('isAllowed')->willReturn(true);

        $this->contextMock->expects($this->any())->method('getUrlBuilder')->willReturn($this->UrlInterfaceMock);
        $this->contextMock->expects($this->any())->method('getButtonList')->willReturn($this->ButtonListMock);
        $this->contextMock->expects($this->any())->method('getRequest')->willReturn($this->RequestInterfaceMock);
        $this->contextMock->expects($this->any())->method('getAuthorization')->willReturn($this->AuthorizationInterfaceMock);
        $this->contextMock->expects($this->any())->method('getEscaper')->willReturn($this->EscaperMock);


        $this->objectManagerHelper = new ObjectManagerHelper($this);
        $this->EditMock = $this->objectManagerHelper->getObject(
            \Moonlay\CustomerAttribute\Block\Adminhtml\Attribute\Edit::class,
            [
                'context' => $this->contextMock,
                'registry' => $this->registryMock,
                'data' => []
            ]
        );
        $expect = __('New Customer Attribute1');
        $this->assertEquals($expect, $this->EditMock->getHeaderText());

    }

    public function testGetHeaderText()
    {

        $this->EscaperMock = $this->getMockBuilder(\Magento\Framework\Escaper::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->registryMock = $this->getMockBuilder(\Magento\Framework\Registry::class)
            ->setMethods(['registry', 'getIsUserDefined', 'getId', 'getFrontendLabel'])
            ->getMock();


        $this->registryMock->expects($this->any())
            ->method('registry')
            ->withConsecutive(
                ['entity_attribute'],
                ['customer_attribute']

            )
            ->willReturnSelf();

        $this->registryMock->expects($this->any())->method('getFrontendLabel')
            ->willReturn(['"something string"']);


        $this->registryMock->expects($this->any())->method('getId')->willReturn(true);

        $this->EscaperMock->expects($this->any())
            ->method('escapeHtml')
            ->with('"something string"')
            ->willReturn("something string");

        $this->AuthorizationInterfaceMock->expects($this->any())->method('isAllowed')->willReturn(true);

        $this->contextMock->expects($this->any())->method('getUrlBuilder')->willReturn($this->UrlInterfaceMock);
        $this->contextMock->expects($this->any())->method('getButtonList')->willReturn($this->ButtonListMock);
        $this->contextMock->expects($this->any())->method('getRequest')->willReturn($this->RequestInterfaceMock);
        $this->contextMock->expects($this->any())->method('getAuthorization')->willReturn($this->AuthorizationInterfaceMock);
        $this->contextMock->expects($this->any())->method('getEscaper')->willReturn($this->EscaperMock);


        $this->objectManagerHelper = new ObjectManagerHelper($this);
        $this->EditMock = $this->objectManagerHelper->getObject(
            \Moonlay\CustomerAttribute\Block\Adminhtml\Attribute\Edit::class,
            [
                'context' => $this->contextMock,
                'registry' => $this->registryMock,
                'data' => []
            ]
        );
        $expect = __('Edit Customer Attribute "%1"', "something string");
        $this->assertEquals($expect, $this->EditMock->getHeaderText());

    }


    public function test_getSaveAndContinueUrl()
    {
        $this->registryMock->expects($this->any())->method('registry')
            ->with('entity_attribute')
            ->willReturnSelf();
        $this->registryMock->expects($this->any())->method('getIsUserDefined')->willReturn(true);
        $this->registryMock->expects($this->any())->method('getId')->willReturn(true);

        $this->AuthorizationInterfaceMock->expects($this->any())->method('isAllowed')->willReturn(true);

//        $this->UrlInterfaceMock->expects($this->any())
//            ->method('getUrl')
//            ->with('attribute/*/save', ['_current' => true, 'back' => 'back', 'active_tab' => 'tab'])
//            ->willReturnSelf();

        $this->contextMock->expects($this->any())->method('getUrlBuilder')->willReturn($this->UrlInterfaceMock);
        $this->contextMock->expects($this->any())->method('getButtonList')->willReturn($this->ButtonListMock);
        $this->contextMock->expects($this->any())->method('getRequest')->willReturn($this->RequestInterfaceMock);
        $this->contextMock->expects($this->any())->method('getAuthorization')->willReturn($this->AuthorizationInterfaceMock);

        $this->objectManagerHelper = new ObjectManagerHelper($this);
        $this->EditMock = $this->objectManagerHelper->getObject(
            \Moonlay\CustomerAttribute\Block\Adminhtml\Attribute\Edit::class,
            [
                'context' => $this->contextMock,
                'registry' => $this->registryMock,
                'data' => []
            ]
        );

        $reflectionClass = new \ReflectionClass(\Moonlay\CustomerAttribute\Block\Adminhtml\Attribute\Edit::class);

        $reflectionMethod = $reflectionClass->getMethod('_getSaveAndContinueUrl');
        $reflectionMethod->setAccessible(true);

        $result=$reflectionMethod->invoke( $this->EditMock,[]);
        $this->assertEquals(null, $result);
    }
}
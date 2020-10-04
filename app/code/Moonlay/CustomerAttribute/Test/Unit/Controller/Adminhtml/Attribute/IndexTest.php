<?php

namespace Magento\Tools\Moonlay\CustomerAttribute\Test\Unit\Controller\Adminhtml\Attribute;

use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use Magento\Framework\View\Page\Config;
use Magento\Framework\View\Page\Title;
use Magento\Framework\View\Result\PageFactory;
use Moonlay\CustomerAttribute\Controller\Adminhtml\Attribute\Index;
use Magento\Backend\App\Action\Context;
use PHPUnit\Framework\TestCase;

class IndexTest extends TestCase
{

    private $authorizationInterfaceMock;
    private $resultFactoryMock;
    private $pageMock;
    private $pageConfigMock;
    private $titleMock;
    private $objectManagerHelper;
    private $indexController;
    private $contextMock;

    protected function setUp()
    {

        $this->resultFactoryMock = $this->getMockBuilder(\Magento\Framework\View\Result\PageFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->pageMock = $this->getMockBuilder(Page::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->pageConfigMock = $this->getMockBuilder(Config::class)
            ->setMethods(['getTitle'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->titleMock = $this->getMockBuilder(Title::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->contextMock = $this->createMock(\Magento\Backend\App\Action\Context::class);

        $this->authorizationInterfaceMock = $this->createMock(\Magento\Framework\AuthorizationInterface::class);
        $this->authorizationInterfaceMock->expects($this->any())
            ->method('isAllowed')
            ->with('Moonlay_CustomerAttribute::customer_attributes')
            ->willReturn(true);

        $this->contextMock->expects($this->any())
            ->method('getAuthorization')
            ->willReturn($this->authorizationInterfaceMock);

        $this->objectManagerHelper = new ObjectManagerHelper($this);
        $this->indexController = $this->objectManagerHelper->getObject(
            \Moonlay\CustomerAttribute\Controller\Adminhtml\Attribute\Index::class,
            [
                'context' => $this->contextMock,
                'resultPageFactory' => $this->resultFactoryMock
            ]
        );
    }

    public function testExecute(){
        $this->resultFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->pageMock);
        $this->pageMock->expects($this->once())
            ->method('setActiveMenu')
            ->with('Moonlay_CustomerAttribute::customer_attributes')
            ->willReturnSelf();
        $this->pageMock->expects($this->exactly(2))
            ->method('addBreadcrumb')
            ->withConsecutive(
                [__('Customer Attribute'), __('CustomerAttribute')],
                [__('Manage Customer Attribute'), __('Customer Attributes')]
            );
        $this->pageMock->expects($this->once())
            ->method('getConfig')
            ->willReturn($this->pageConfigMock);
        $this->pageConfigMock->expects($this->once())->method('getTitle')->willReturn($this->titleMock);
        $this->titleMock->expects($this->once())->method('prepend')->with(__('Customer Attributes'))->willReturn($this->pageMock);

        $this->assertSame($this->pageMock, $this->indexController->execute());

    }

    public function test_isAllowed()
    {
        $refClass = new \ReflectionClass(\Moonlay\CustomerAttribute\Controller\Adminhtml\Attribute\Index::class);
        $isAllowed = $refClass->getMethod('_isAllowed');
        $isAllowed->setAccessible(true);
        $this->assertEquals(true, $isAllowed->invoke($this->indexController, ['Moonlay_CustomerAttribute::customer_attributes']));
    }


}

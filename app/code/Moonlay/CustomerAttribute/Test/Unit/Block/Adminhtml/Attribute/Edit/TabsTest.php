<?php

namespace Moonlay\CustomerAttribute\Test\Unit\Block\Adminhtml\Attribute\Edit;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use Moonlay\CustomerAttribute\Block\Adminhtml\Attribute\Edit\Tabs;

class TabsTest extends \PHPUnit\Framework\TestCase
{

    // \Magento\Backend\Block\Template\Context $context,
    // \Magento\Framework\Json\EncoderInterface $jsonEncoder,
    // \Magento\Backend\Model\Auth\Session $authSession,

    /**
     * @var instance object
     */
    private $tabMock;

    /**
     * @var instance object
     */
    private $contextMock;

    /**
     * @var instance object
     */
    private $jsonEncoderMock;
    /**
     * @var instance object
     */
    private $authSessionMock;

    private $objectManagerHelper;
    private $LayoutInterfaceMock;
    private $requestMock;

    protected function setUp()
    {
        $this->LayoutInterfaceMock = $this->createMock(\Magento\Framework\View\LayoutInterface::class);
        $this->requestMock = $this->createMock(\Magento\Framework\App\RequestInterface::class);
        $this->contextMock = $this->createMock(\Magento\Backend\Block\Template\Context::class);
        $this->jsonEncoderMock = $this->createMock(\Magento\Framework\Json\EncoderInterface::class);
        $this->authSessionMock = $this->createMock(\Magento\Backend\Model\Auth\Session::class);


        $this->contextMock->expects($this->any())
            ->method('getRequest')
            ->willReturn($this->requestMock);

        $this->contextMock->expects($this->any())
            ->method('getLayout')
            ->willReturn($this->LayoutInterfaceMock);

        $this->objectManagerHelper = new ObjectManagerHelper($this);
        $this->tabMock = $this->objectManagerHelper->getObject(
            \Moonlay\CustomerAttribute\Block\Adminhtml\Attribute\Edit\Tabs::class,
            [
                'context' => $this->contextMock,
                'jsonEncoder' => $this->jsonEncoderMock,
                'authSession' => $this->authSessionMock
            ]
        );
    }

    public function test_beforeToHtml()
    {
        $refClass = new \ReflectionClass(\Moonlay\CustomerAttribute\Block\Adminhtml\Attribute\Edit\Tabs::class);
        $_beforeToHtml = $refClass->getMethod('_beforeToHtml');
        $_beforeToHtml->setAccessible(true);
        $this->assertInstanceOf('\Magento\Framework\DataObject', $_beforeToHtml->invoke($this->tabMock, []));

    }


}
<?php
/**
 * Customer attribute controller
 */

namespace Moonlay\CustomerAttribute\Test\Unit\Controller\Adminhtml\Attribute;

use Moonlay\CustomerAttribute\Controller\Adminhtml\Attribute\NewAction;

class NewActionTest extends \PHPUnit\Framework\TestCase
{

    private $contextMock;
    private $resultForwardFactory;
    private $NewActionbBlock;
    private $authorizationInterfaceMock;
    private $resultForwardMock;
    private $resultMock;


    protected function setUp()
    {
        $this->authorizationInterfaceMock = $this->createMock(\Magento\Framework\AuthorizationInterface::class);
        $this->contextMock = $this->createMock(\Magento\Backend\App\Action\Context::class);

        $this->resultMock = $this->createMock(\Magento\Framework\Controller\Result\Forward::class);
        $this->resultForwardMock = $this->createMock(\Magento\Backend\Model\View\Result\Forward::class);

        $this->resultForwardFactory = $this->getMockBuilder(\Magento\Backend\Model\View\Result\ForwardFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create', 'forward'])
            ->getMock();

        $this->authorizationInterfaceMock->expects($this->any())
            ->method('isAllowed')
            ->with('Moonlay_CustomerAttribute::save')
            ->willReturn(true);

        $this->contextMock->expects($this->any())
            ->method('getAuthorization')
            ->willReturn($this->authorizationInterfaceMock);

        $objectManagerHelper = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->NewActionbBlock = $objectManagerHelper->getObject(\Moonlay\CustomerAttribute\Controller\Adminhtml\Attribute\NewAction::class, [
            'context' => $this->contextMock,
            'ForwardFactory' => $this->resultForwardFactory
        ]);
    }

    public function test_isAllowed()
    {

        $refClass = new \ReflectionClass(\Moonlay\CustomerAttribute\Controller\Adminhtml\Attribute\NewAction::class);
        $isAllowed = $refClass->getMethod('_isAllowed');
        $isAllowed->setAccessible(true);

           $this->assertEquals(true,$isAllowed->invoke($this->NewActionbBlock, ['Moonlay_CustomerAttribute::save']));
    }

    public function testExecute()
    {

        $this->NewActionbBlock = new \Moonlay\CustomerAttribute\Controller\Adminhtml\Attribute\NewAction(
            $this->contextMock,
            $this->resultForwardFactory
        );

        $this->resultForwardFactory->expects($this->any())
            ->method('create')
            ->willReturn($this->resultForwardMock);

        $this->resultForwardFactory->expects($this->any())
            ->method('forward')
            ->with('edit')
            ->willReturn($this->resultMock);

        $this->assertEquals(null, $this->NewActionbBlock->execute());

    }

}
<?php

namespace Moonlay\CustomerAttribute\Test\Unit\Controller\Adminhtml\Attribute;

use Moonlay\CustomerAttribute\Controller\Adminhtml\Attribute\Delete;
use Magento\Framework\Exception\NoSuchEntityException;

class DeleteTest extends \PHPUnit\Framework\TestCase
{

    private $deleteMock;
    private $contextMock;
    private $requestMock;
    private $authorizationInterfaceMock;
    private $redirectFactoryMock;
    private $redirectMock;
    private $objManagerMock;
    private $attributeMock;
    private $messageManagerMock;


    protected function setUp()
    {
        $this->authorizationInterfaceMock = $this->createMock(\Magento\Framework\AuthorizationInterface::class);
        $this->requestMock = $this->createMock(\Magento\Framework\App\RequestInterface::class);

        $this->authorizationInterfaceMock->expects($this->any())
            ->method('isAllowed')
            ->with('Moonlay_CustomerAttribute::attribute_delete')
            ->willReturn(true);

        $this->redirectMock = $this->createMock(\Magento\Framework\Controller\Result\Redirect::class);

        $this->redirectFactoryMock = $this->createMock(\Magento\Framework\Controller\Result\RedirectFactory::class);
        $this->redirectFactoryMock->expects($this->any())->method('create')
            ->willReturn($this->redirectMock);

        $this->objManagerMock = $this->createMock(\Magento\Framework\ObjectManagerInterface::class);
        $this->messageManagerMock = $this->createMock(\Magento\Framework\Message\ManagerInterface::class);

        $this->attributeMock = $this->createMock(\Magento\Customer\Model\Attribute::class);
        $this->contextMock = $this->createMock(\Magento\Backend\App\Action\Context::class);
        $this->contextMock->expects($this->any())
            ->method('getResultRedirectFactory')
            ->willReturn($this->redirectFactoryMock);

        $this->contextMock->expects($this->any())
            ->method('getObjectManager')
            ->willReturn($this->objManagerMock);

        $this->contextMock->expects($this->any())
            ->method('getAuthorization')
            ->willReturn($this->authorizationInterfaceMock);

        $this->contextMock->expects($this->any())
            ->method('getMessageManager')
            ->willReturn($this->messageManagerMock);

        $objectManagerHelper = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->deleteMock = $objectManagerHelper->getObject(\Moonlay\CustomerAttribute\Controller\Adminhtml\Attribute\Delete::class
            , ['context' => $this->contextMock]);
    }

    public function test_isAllowed()
    {

        $refClass = new \ReflectionClass(Delete::class);
        $isAllowed = $refClass->getMethod('_isAllowed');
        $isAllowed->setAccessible(true);


          $this->assertEquals(true,$isAllowed->invoke($this->deleteMock, ['Moonlay_CustomerAttribute::attribute_delete']));
    }

    /**
     * @param bool $id
     * @param string $key
     * @param string $phrase
     * @dataProvider gestSuccesDeleteAttributeProvider
     */
    public function testGestSuccesDeleteAttribute($id, $key, $phrase)
    {
        $this->requestMock->expects($this->any())
            ->method('getParam')
            ->with($key)
            ->will($this->returnValue($id));

        $this->contextMock->expects($this->any())
            ->method('getRequest')
            ->willReturn($this->requestMock);

        $this->redirectMock->expects($this->any())
            ->method('setPath')
            ->with('*/*/')
            ->willReturnSelf();

        $this->redirectFactoryMock->expects($this->any())->method('create')
            ->willReturn($this->redirectMock);

        $this->contextMock->expects($this->any())
            ->method('getResultRedirectFactory')
            ->willReturn($this->redirectFactoryMock);

        $this->objManagerMock->expects($this->any())
            ->method('create')
            ->with('Magento\Customer\Model\Attribute')
            ->willReturn($this->attributeMock);

        $this->attributeMock->expects($this->any())
            ->method('load')
            ->with($id)
            ->willReturnSelf();

        $this->attributeMock->expects($this->any())
            ->method('delete')
            ->willReturnSelf();

        $result = $this->messageManagerMock->expects($this->any())
            ->method('addSuccess')
            ->with($phrase)
            ->willReturnSelf();

        $objectManagerHelper = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->deleteMock = $objectManagerHelper->getObject(\Moonlay\CustomerAttribute\Controller\Adminhtml\Attribute\Delete::class
            , ['context' => $this->contextMock]);

        $this->assertInstanceOf(\Magento\Framework\Controller\Result\Redirect::class, $this->deleteMock->execute());

    }

    /**
     * @return array
     */
    public function gestSuccesDeleteAttributeProvider()
    {
        return [
            [true, 'attribute_id', __('The attribute has been deleted.')]
        ];
    }


    /**
     * @param bool $id
     * @param string $key
     * @param string $phrase
     * @dataProvider getFailedDeleteAttributeProvider
     */
    public function testGetFailedDeleteAttribute($id, $key, $phrase)
    {
        $this->requestMock->expects($this->any())
            ->method('getParam')
            ->with($key)
            ->will($this->returnValue($id));

        $this->contextMock->expects($this->any())
            ->method('getRequest')
            ->willReturn($this->requestMock);

        $objectManagerHelper = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->deleteMock = $objectManagerHelper->getObject(\Moonlay\CustomerAttribute\Controller\Adminhtml\Attribute\Delete::class
            , ['context' => $this->contextMock]);

        $this->assertEquals(null, $this->deleteMock->execute());
    }

    /**
     * @return array
     */
    public function getFailedDeleteAttributeProvider()
    {
        return [
            [false, 'attribute_id', __('We can\'t find a attribute to delete.')]
        ];
    }


    /**
     * @param bool $id
     * @param string $key
     * @param string $phrase
     * @dataProvider getFailedDeleteAttributeExProvider
     */
    public function testGetFailedDeleteAttributeEx($id, $key, $phrase)
    {
        $this->requestMock->expects($this->any())
            ->method('getParam')
            ->with($key)
            ->will($this->returnValue($id));

        $this->contextMock->expects($this->any())
            ->method('getRequest')
            ->willReturn($this->requestMock);

        $this->redirectMock->expects($this->any())
            ->method('setPath')
            ->with('*/*/edit', ['id' => $id])
            ->willReturnSelf();

        $this->redirectFactoryMock->expects($this->any())->method('create')
            ->willReturn($this->redirectMock);

        $this->contextMock->expects($this->any())
            ->method('getResultRedirectFactory')
            ->willReturn($this->redirectFactoryMock);

        $this->objManagerMock->expects($this->any())
            ->method('create')
            ->with('Magento\Customer\Model\Attribute')
            ->willReturn($this->attributeMock);
        $exception = new \Exception('Bad request.');
        $this->attributeMock->expects($this->any())
            ->method('load')
            ->with($id)
            ->willThrowException($exception);

        $this->attributeMock->expects($this->any())
            ->method('delete')
            ->willReturnSelf();

        $result = $this->messageManagerMock->expects($this->any())
            ->method('addSuccess')
            ->with($phrase)
            ->willReturnSelf();

        $objectManagerHelper = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->deleteMock = $objectManagerHelper->getObject(\Moonlay\CustomerAttribute\Controller\Adminhtml\Attribute\Delete::class
            , ['context' => $this->contextMock]);

        $this->assertInstanceOf(\Magento\Framework\Controller\Result\Redirect::class, $this->deleteMock->execute());

    }

    /**
     * @return array
     */
    public function getFailedDeleteAttributeExProvider()
    {
        return [
            [true, 'attribute_id', __('The attribute has been deleted.')]
        ];
    }

}
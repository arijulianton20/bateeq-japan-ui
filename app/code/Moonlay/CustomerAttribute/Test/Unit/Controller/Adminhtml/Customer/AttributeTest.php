<?php
/**
 * Customer attribute controller
 */

namespace Moonlay\CustomerAttribute\Test\Unit\Controller\Adminhtml\Customer;


class AttributeTest extends \PHPUnit\Framework\TestCase
{

    private $actionFlagMock;
    private $authMock;
    private $authorizationMock;
    private $eventManagerMock;
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    private $pageFactoryMock;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $registryMock;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    private $requestMock;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    private $attributMock;

    /**
     * @var ObjectManagerInterface
     */
    private $objectManagerInterfaceMock;

    /**
     * @var string
     */
    private $instanceNameMock;
    /**
     * @var \Magento\Backend\App\Action\Context $context
     */
    private $contextMock;
    private $entityMock;
    private $formKeyValidatorMock;
    private $urlMock;
    private $abstractActionMock;
    private $responseMock;
    private $sessionMock;

    private $storageInterfaceMock;


    protected function setUp()
    {
        $this->storageInterfaceMock = $this->getMockBuilder(\Magento\Backend\Model\Auth\StorageInterface::class)
        ->disableOriginalConstructor()
        ->setMethods(['isFirstPageAfterLogin'])
        ->getMockForAbstractClass();


        $this->actionFlagMock = $this->getMockBuilder(\Magento\Framework\App\ActionFlag::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->authMock = $this->getMockBuilder(\Magento\Backend\Model\Auth::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->authorizationMock = $this->getMockBuilder(\Magento\Framework\AuthorizationInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->abstractActionMock = $this->createMock(\Magento\Backend\App\AbstractAction::class);

        $this->eventManagerMock = $this->getMockBuilder(\Magento\Framework\Event\ManagerInterface::class)
            ->getMock();
        $this->responseMock = $this->createMock(\Magento\Framework\App\ResponseInterface::class);
        $this->sessionMock = $this->getMockBuilder(\Magento\Backend\Model\Session::class)
            ->disableOriginalConstructor()
            ->setMethods(['setSessionLocale', 'getLocale'])
            ->getMock();

        $this->entityMock = $this->createMock(\Magento\Eav\Model\Entity\AbstractEntity::class);
        $this->formKeyValidatorMock = $this->getMockBuilder(\Magento\Framework\Data\Form\FormKey\Validator::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->requestMock = $this->getMockBuilder(\Magento\Framework\App\RequestInterface::class)
            ->setMethods(['isPost', 'isDispatched', 'getFullActionName', 'getRouteName'])
            ->getMockForAbstractClass();

        $this->urlMock = $this->createMock(\Magento\Catalog\Model\Product\Url::class);

        $this->objectManagerInterfaceMock = $this->getMockBuilder(\Magento\Framework\ObjectManagerInterface::class)
            ->setMethods(['isValid'])
            ->getMockForAbstractClass();

        $this->pageFactoryMock = $this->createMock(\Magento\Framework\View\Result\PageFactory::class);

        $this->contextMock = $this->createMock(\Magento\Backend\App\Action\Context::class);

        $this->contextMock->expects($this->any())
            ->method('getObjectManager')
            ->willReturn($this->objectManagerInterfaceMock);

        $this->contextMock->expects($this->any())
            ->method('getAuth')
            ->willReturn($this->authMock);

        $this->contextMock->expects($this->any())
            ->method('getRequest')
            ->willReturn($this->requestMock);

        $this->contextMock->expects($this->any())
            ->method('getFormKeyValidator')
            ->willReturn($this->formKeyValidatorMock);

        $this->contextMock->expects($this->any())
            ->method('getAuthorization')
            ->willReturn($this->authorizationMock);

        $this->contextMock->expects($this->any())
            ->method('getActionFlag')
            ->willReturn($this->actionFlagMock);

        $this->contextMock->expects($this->any())
            ->method('getSession')
            ->willReturn($this->sessionMock);

        $this->contextMock->expects($this->any())
            ->method('getEventManager')
            ->willReturn($this->eventManagerMock);

        $this->registryMock = $this->createMock(\Magento\Framework\Registry::class);

        $this->attributMock = $this->getMockForAbstractClass(\Moonlay\CustomerAttribute\Controller\Adminhtml\Customer\Attribute::class,
            array(

                'context' => $this->contextMock,
                'coreRegistry' => $this->registryMock,
                'resultPageFactory' => $this->pageFactoryMock
            ),
            '',
            true,
            TRUE,
            TRUE,
            []);
    }


    /**
     * Test the Attribute::testDispatch() method.
     *
     */
    public function testDispatch()
    {
        $this->objectManagerInterfaceMock->expects($this->any())
            ->method('create')
            ->with('Magento\Eav\Model\Entity')
            ->willReturn($this->entityMock);

        $this->entityMock->expects($this->any())
            ->method('setType')
            ->with(\Magento\Customer\Model\Customer::ENTITY)
            ->willReturnSelf();

        $this->entityMock->expects($this->any())
            ->method('getTypeId')
            ->willReturn(\Magento\Customer\Model\Customer::ENTITY);

        $this->authMock->expects($this->any())->method('isLoggedIn')->willReturn(true);

        $this->authMock->expects($this->any())
            ->method('getAuthStorage')
            ->willReturn($this->storageInterfaceMock);

        $this->storageInterfaceMock->expects($this->any())
            ->method('isFirstPageAfterLogin')
            ->willReturn(true);

        $this->requestMock->expects($this->any())->method('isPost')->willReturn(true);

        $this->formKeyValidatorMock->expects($this->any())->method('validate')->willReturn(true);

        $this->requestMock->expects($this->any())->method('isDispatched')->willReturn(true);
        $this->authorizationMock->expects($this->any())->method('isAllowed')->willReturn(true);

        $this->actionFlagMock->expects($this->any())->method('get')
            ->withConsecutive(['', \Magento\Backend\App\AbstractAction::FLAG_IS_URLS_CHECKED],
                ['', \Magento\Framework\App\ActionInterface::FLAG_NO_DISPATCH])
            ->willReturnOnConsecutiveCalls(true, true);

        $this->objectManagerInterfaceMock->expects($this->any())->method('get')
            ->with(\Magento\Framework\Validator\Locale::class)
            ->willReturnSelf();

        $this->objectManagerInterfaceMock->expects($this->any())->method('isValid')
            ->willReturn(true);

        $this->sessionMock->expects($this->any())->method('setSessionLocale')
            ->with(null)
            ->willReturnSelf();

        $this->sessionMock->expects($this->any())->method('getLocale')
            ->willReturnSelf();

        $this->requestMock->expects($this->any())->method('getFullActionName');


        $eventParameters = ['controller_action' => $this->attributMock, 'request' => $this->requestMock];
        $routename = 'any_route_name';
        $this->requestMock->expects($this->any())->method('getRouteName')->willReturn($routename);
        $this->eventManagerMock->expects($this->any())->method('dispatch')
            ->withConsecutive(['controller_action_predispatch', $eventParameters],
                ['controller_action_predispatch_' . $routename, $eventParameters])
            ->willReturnSelf();

        $this->attributMock->dispatch($this->requestMock);

    }


    /**
     * @param string $label An attribute code
     * @param string $expected The attribute's store label
     * @dataProvider generateCodeValidProvider
     */

    public function testGenerateCodeValid($label)
    {

        $this->objectManagerInterfaceMock->expects($this->once())
            ->method('create')
            ->with('Magento\Catalog\Model\Product\Url')
            ->willReturn($this->urlMock);

        $this->urlMock->expects($this->once())
            ->method('formatUrlKey')
            ->with($label)
            ->willReturn($label);

        $reflectionClass = new \ReflectionClass(\Moonlay\CustomerAttribute\Controller\Adminhtml\Customer\Attribute::class);

        $reflectionGenerateCode = $reflectionClass->getMethod('generateCode');
        $reflectionGenerateCode->setAccessible(true);

        $result = $reflectionGenerateCode->invoke($this->attributMock, $label);

        $expected = 'product_color';
        $this->assertEquals($expected, $result);

    }

    /**
     * @return array
     */
    public function generateCodeValidProvider()
    {
        return [
            ['product_color']
        ];
    }

    /**
     * @param string $label An attribute code
     * @param string $expected The attribute's store label
     * @dataProvider generateCodeUnvalidProvider
     */
    public function testGenerateCodeUnvalid($label)
    {

        $this->objectManagerInterfaceMock->expects($this->once())
            ->method('create')
            ->with('Magento\Catalog\Model\Product\Url')
            ->willReturn($this->urlMock);

        $this->urlMock->expects($this->once())
            ->method('formatUrlKey')
            ->with($label)
            ->willReturn($label);

        $reflectionClass = new \ReflectionClass(\Moonlay\CustomerAttribute\Controller\Adminhtml\Customer\Attribute::class);

        $reflectionGenerateCode = $reflectionClass->getMethod('generateCode');
        $reflectionGenerateCode->setAccessible(true);

        $result = $reflectionGenerateCode->invoke($this->attributMock, $label);

        $expected = 'attr__product_color';
        $this->assertEquals($expected, $result);

    }

    /**
     * @return array
     */
    public function generateCodeUnvalidProvider()
    {
        return [
            ['/product_color'],
        ];
    }


}
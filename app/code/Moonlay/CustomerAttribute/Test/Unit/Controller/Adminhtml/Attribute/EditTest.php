<?php

namespace Magento\Tools\Moonlay\CustomerAttribute\Test\Unit\Controller\Adminhtml\Attribute;

;

use Magento\Framework\View\Page\Title;
use Moonlay\CustomerAttribute\Controller\Adminhtml\Attribute\Edit;
use PHPUnit\Framework\TestCase;
use Magento\Framework\App\RequestInterface;

class EditTest extends TestCase
{

    private $actionFlagMock;
    private $AttributeMock;
    private $contextMock;
    private $resultPageFactoryMock;
    private $LayoutInterfaceMock;
    private $LocaleMock;
    private $modelPage;
    private $ManagerInterfaceMock;
    private $authorizationInterfaceMock;
    private $registry;
    private $requestInterfaceMock;
    private $editMock;
    private $EntityMock;
    private $formKeyValidatorMock;
    private $resultRedirectFactoryMock;
    private $resultRedirectMock;
    private $frameworkPage;
    private $ObjectManagerInterfaceMock;
    private $authMock;
    private $urlMock;
    private $configMock;
    private $titleMock;
    private $SessionMock;
    private $eventManagerMock;
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
        $this->AttributeMock = $this->getMockBuilder(\Magento\Customer\Model\Attribute::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->eventManagerMock = $this->getMockBuilder(\Magento\Framework\Event\ManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->urlMock = $this->createMock(\Magento\Catalog\Model\Product\Url::class);

        $this->authMock = $this->getMockBuilder(\Magento\Backend\Model\Auth::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->authMock->expects($this->any())
            ->method('isLoggedIn')
            ->willReturn(true);


        $this->authMock->expects($this->any())
            ->method('getAuthStorage')
            ->willReturn($this->storageInterfaceMock);

        $this->storageInterfaceMock->expects($this->any())
            ->method('isFirstPageAfterLogin')
            ->willReturn(true);

            

        $this->configMock = $this->getMockBuilder(\Magento\Framework\View\Page\Config::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->EntityMock = $this->getMockBuilder(\Magento\Eav\Model\Entity::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->LocaleMock = $this->getMockBuilder(\Magento\Framework\Validator\Locale::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->titleMock = $this->getMockBuilder(Title::class)
            ->disableOriginalConstructor()
            ->getMock();


        $this->resultPageFactoryMock = $this->getMockBuilder(\Magento\Framework\View\Result\PageFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->resultRedirectFactoryMock = $this->getMockBuilder(\Magento\Framework\Controller\Result\RedirectFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->resultRedirectMock = $this->createMock(\Magento\Framework\Controller\Result\Redirect::class);

        $this->frameworkPage = $this->createMock(\Magento\Framework\View\Result\Page::class);
        $this->formKeyValidatorMock = $this->createMock(\Magento\Framework\Data\Form\FormKey\Validator::class);
        $this->SessionMock = $this->getMockBuilder(\Magento\Backend\Model\Session::class)
            ->setMethods(['getAttributeData', 'setSessionLocale', 'getLocale'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->LayoutInterfaceMock = $this->getMockBuilder(\Magento\Framework\View\LayoutInterface::class)
            ->getMock();

        $this->modelPage = $this->getMockBuilder(\Magento\Backend\Model\View\Result\Page::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->ManagerInterfaceMock = $this->getMockBuilder(\Magento\Framework\Message\ManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->requestInterfaceMock = $this->getMockBuilder(\Magento\Framework\App\RequestInterface::class)
            ->setMethods(['isPost', 'isDispatched', 'getFullActionName', 'getRouteName'])
            ->getMockForAbstractClass();

        $this->ObjectManagerInterfaceMock = $this->getMockBuilder(\Magento\Framework\ObjectManagerInterface::class)
            ->disableOriginalConstructor()
            // ->setMethods(['create','setType','getTypeId'])
            ->getMock();

        $this->authorizationInterfaceMock = $this->createMock(\Magento\Framework\AuthorizationInterface::class);
        $this->authorizationInterfaceMock->expects($this->any())
            ->method('isAllowed')
            ->with('Moonlay_CustomerAttribute::save')
            ->willReturn(true);

        $this->contextMock = $this->createMock(\Magento\Backend\App\Action\Context::class);
        $this->contextMock->expects($this->any())
            ->method('getAuthorization')
            ->willReturn($this->authorizationInterfaceMock);
            
         

        $this->contextMock->expects($this->any())
            ->method('getActionFlag')
            ->willReturn($this->actionFlagMock);
        $this->contextMock->expects($this->any())
            ->method('getFormKeyValidator')
            ->willReturn($this->formKeyValidatorMock);


        $this->contextMock->expects($this->any())
            ->method('getObjectManager')
            ->willReturn($this->ObjectManagerInterfaceMock);

        $this->contextMock->expects($this->any())
            ->method('getAuth')
            ->willReturn($this->authMock);

        $this->contextMock->expects($this->any())
            ->method('getEventManager')
            ->willReturn($this->eventManagerMock);

        $this->contextMock->expects($this->any())
            ->method('getRequest')
            ->willReturn($this->requestInterfaceMock);

        $this->contextMock->expects($this->any())
            ->method('getResultRedirectFactory')
            ->willReturn($this->resultRedirectFactoryMock);

        $this->contextMock->expects($this->any())
            ->method('getSession')
            ->willReturn($this->SessionMock);


        $this->contextMock->expects($this->any())
            ->method('getMessageManager')
            ->willReturn($this->ManagerInterfaceMock);

        $this->registryMock = $this->createMock(\Magento\Framework\Registry::class);
        $objectManagerHelper = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->editMock = $objectManagerHelper->getObject(\Moonlay\CustomerAttribute\Controller\Adminhtml\Attribute\Edit::class,
            [
                'context' => $this->contextMock,
                'resultPageFactory' => $this->resultPageFactoryMock,
                'registry' => $this->registryMock
            ]);

    }

    public function test_isAllowed()
    {

        $refClass = new \ReflectionClass(Edit::class);
        $_isAllowed = $refClass->getMethod('_isAllowed');
        $_isAllowed->setAccessible(true);

        $result = $_isAllowed->invoke($this->editMock, []);
        $this->assertEquals(true, $result);

    }

    public function test_initAction()
    {

        $this->resultPageFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->modelPage);

        $this->modelPage->expects($this->once())
            ->method('setActiveMenu')
            ->with('Moonlay_CustomerAttribute::customer_attributes')
            ->willReturnSelf();

        $this->modelPage->expects($this->exactly(2))
            ->method('addBreadcrumb')
            ->withConsecutive(
                [__('Customer Attributes'), __('Customer Attributes')],
                [__('Manage Customer Attributes'), __('Manage Customer Attributes')]
            )
            ->willReturnSelf();

        $refClass = new \ReflectionClass(Edit::class);
        $_initAction = $refClass->getMethod('_initAction');
        $_initAction->setAccessible(true);

        $result = $_initAction->invoke($this->editMock, []);
        $this->assertInstanceOf(\Magento\Backend\Model\View\Result\Page::class, $result);
    }

    public function testExecuteAttributeNoExist()
    {
        $this->requestInterfaceMock->expects($this->any())
            ->method('getParam')
            ->withConsecutive(['attribute_id'],
                ['attribute'])
            ->willReturnOnConsecutiveCalls(['validattributeid'], ['validattribute']);

        $this->ObjectManagerInterfaceMock->expects($this->any())
            ->method('create')
            ->with('Magento\Customer\Model\Attribute')
            ->willReturn($this->AttributeMock);

        $this->AttributeMock->expects($this->any())
            ->method('setEntityTypeId')
            ->with(null)
            ->willReturnSelf();

        $this->AttributeMock->expects($this->any())
            ->method('getId')
            ->willReturn(false);

        $this->ManagerInterfaceMock->expects($this->any())
            ->method('addError')
            ->with(__('This attribute no longer exists.'))
            ->willReturnSelf();

        $this->resultRedirectFactoryMock->expects($this->any())
            ->method('create')
            ->willReturn($this->resultRedirectMock);


        $this->resultRedirectMock->expects($this->any())
            ->method('setPath')
            ->with('customerattribute/*/')
            ->willReturnSelf();

        $this->assertInstanceOf(\Magento\Framework\Controller\Result\Redirect::class, $this->editMock->execute());
    }

    public function testExecuteAttributeNoEdited()
    {
        $this->requestInterfaceMock->expects($this->any())
            ->method('getParam')
            ->withConsecutive(['attribute_id'],
                ['attribute'])
            ->willReturnOnConsecutiveCalls(['validattributeid'], ['validattribute']);

        $this->ObjectManagerInterfaceMock->expects($this->any())
            ->method('create')
            ->with('Magento\Customer\Model\Attribute')
            ->willReturn($this->AttributeMock);

        $this->AttributeMock->expects($this->any())
            ->method('setEntityTypeId')
            ->with(null)
            ->willReturnSelf();

        $this->AttributeMock->expects($this->any())
            ->method('getId')
            ->willReturn(true);

        $this->AttributeMock->expects($this->any())
            ->method('getEntityTypeId')
            ->willReturn(true);

        $this->ManagerInterfaceMock->expects($this->any())
            ->method('addError')
            ->with(__('This attribute cannot be edited.'))
            ->willReturnSelf();

        $this->resultRedirectFactoryMock->expects($this->any())
            ->method('create')
            ->willReturn($this->resultRedirectMock);


        $this->resultRedirectMock->expects($this->any())
            ->method('setPath')
            ->with('customerattribute/*/')
            ->willReturnSelf();

        $this->assertInstanceOf(\Magento\Framework\Controller\Result\Redirect::class, $this->editMock->execute());
    }

    public function testExecuteAttributeSucces()
    {
        $this->requestInterfaceMock->expects($this->any())
            ->method('getParam')
            ->withConsecutive(['attribute_id'],
                ['attribute'])
            ->willReturnOnConsecutiveCalls(null, ['validattribute']);

        $this->ObjectManagerInterfaceMock->expects($this->any())
            ->method('create')
            ->with('Magento\Customer\Model\Attribute')
            ->willReturn($this->AttributeMock);

        $this->AttributeMock->expects($this->any())
            ->method('setEntityTypeId')
            ->with(null)
            ->willReturnSelf();

        $this->AttributeMock->expects($this->any())
            ->method('getId')
            ->willReturn(true);

        $this->AttributeMock->expects($this->any())
            ->method('getEntityTypeId')
            ->willReturn(false);


        $this->ObjectManagerInterfaceMock->expects($this->any())
            ->method('get')
            ->with('Magento\Backend\Model\Session')
            ->willReturn($this->SessionMock);

        $this->SessionMock->expects($this->any())
            ->method('getAttributeData')
            ->with(true)
            ->willReturn(array('data1', 'data2'));

        $this->resultPageFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->modelPage);

        $this->modelPage->expects($this->exactly(3))
            ->method('addBreadcrumb')
            ->withConsecutive(
                [__('Customer'), __('Customer')],
                [__('Manage Customer Attributes'), __('Manage Customer Attributes')]
            )
            ->willReturnSelf();

        $this->modelPage->expects($this->any())
            ->method('getConfig')
            ->willReturn($this->configMock);

        $this->configMock->expects($this->any())->method('getTitle')->willReturn($this->titleMock);


        $this->modelPage->expects($this->any())
            ->method('getLayout')
            ->willReturn($this->LayoutInterfaceMock);

        $this->LayoutInterfaceMock->expects($this->any())
            ->method('getBlock')
            ->with('attribute_edit_js')
            ->willReturnSelf();

        $this->assertInstanceOf(\Magento\Backend\Model\View\Result\Page::class, $this->editMock->execute());
    }


    public function testDispatch()
    {
        $this->ObjectManagerInterfaceMock->expects($this->any())
            ->method('create')
            ->with('Magento\Eav\Model\Entity')
            ->willReturn($this->EntityMock);

        $this->EntityMock->expects($this->any())
            ->method('setType')
            ->with(\Magento\Customer\Model\Customer::ENTITY)
            ->willReturnSelf();

        $this->EntityMock->expects($this->any())
            ->method('getTypeId')
            ->willReturnSelf();

        $this->requestInterfaceMock->expects($this->any())->method('isPost')->willReturn(true);

        $this->formKeyValidatorMock->expects($this->any())->method('validate')->willReturn($this->requestInterfaceMock);
        $this->requestInterfaceMock->expects($this->any())->method('isDispatched')->willReturn(true);

        $this->actionFlagMock->expects($this->any())->method('get')
            ->withConsecutive(['', \Magento\Backend\App\AbstractAction::FLAG_IS_URLS_CHECKED], ['', \Magento\Framework\App\ActionInterface::FLAG_NO_DISPATCH])
            ->willReturn(true);

        $this->ObjectManagerInterfaceMock->expects($this->any())->method('get')
            ->with(\Magento\Framework\Validator\Locale::class)
            ->willReturn($this->LocaleMock);

        $this->LocaleMock->expects($this->any())->method('isValid')
            ->with(null)
            ->willReturn(true);

        $this->SessionMock->expects($this->any())->method('setSessionLocale')
            ->with(null)
            ->willReturnSelf();

        $this->SessionMock->expects($this->any())->method('getLocale')
            ->willReturnSelf();

        $this->requestInterfaceMock->expects($this->any())->method('getFullActionName');
//            ->willReturnSelf();

        $eventParameters = ['controller_action' => $this->editMock, 'request' => $this->requestInterfaceMock];
        $routename='any_route_name';
        $this->requestInterfaceMock->expects($this->any())->method('getRouteName')->willReturn($routename);

        $this->eventManagerMock->expects($this->any())->method('dispatch')
            ->withConsecutive(['controller_action_predispatch', $eventParameters],
                ['controller_action_predispatch_'.$routename, $eventParameters])
            ->willReturnSelf();

        $this->editMock->dispatch($this->requestInterfaceMock);

    }


    public function testCreateActionPage()
    {
        $this->resultPageFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->modelPage);

        $this->modelPage->expects($this->exactly(3))
            ->method('addBreadcrumb')
            ->withConsecutive(
                [__('Customer'), __('Customer')],
                [__('Manage Customer Attributes'), __('Manage Customer Attributes')]
            )
            ->willReturnSelf();

        $this->modelPage->expects($this->once())
            ->method('getConfig')
            ->willReturn($this->configMock);

        $this->configMock->expects($this->once())->method('getTitle')->willReturn($this->titleMock);

        $refClass = new \ReflectionClass(Edit::class);
        $createActionPage = $refClass->getMethod('createActionPage');
        $createActionPage->setAccessible(true);

        $result = $createActionPage->invoke($this->editMock, 'anytitle');
        $this->assertSame($this->modelPage, $result);
        $this->assertInstanceOf(\Magento\Backend\Model\View\Result\Page::class, $result);
    }

    /**
     * @param string $label An attribute code
     * @param string $expected The attribute's store label
     * @dataProvider generateCodeValidProvider
     */
    public function testGenerateCode($expected, $label)
    {
        $reflectionClass = new \ReflectionClass(\Moonlay\CustomerAttribute\Controller\Adminhtml\Attribute\Edit::class);

        $reflectionMethod = $reflectionClass->getMethod('generateCode');
        $reflectionMethod->setAccessible(true);


        $this->ObjectManagerInterfaceMock->expects($this->once())
            ->method('create')
            ->with('Magento\Catalog\Model\Product\Url')
            ->willReturn($this->urlMock);

        $this->urlMock->expects($this->once())
            ->method('formatUrlKey')
            ->with($label)
            ->willReturn($label);

        $result=$reflectionMethod->invoke( $this->editMock,$label);
        $this->assertEquals($expected, $result);

    }

    /**
     * @return array
     */
    public function generateCodeValidProvider()
    {
        return [
            ['anylabel', 'anylabel'],

        ];
    }

    /**
     * @param string $expected The attribute's store label
     * @param string $label An attribute code
     * @dataProvider generateCodeUnvalidProvider
     */
    public function testGenerateCodeUnvalid($expected, $label)
    {
        $reflectionClass = new \ReflectionClass(\Moonlay\CustomerAttribute\Controller\Adminhtml\Attribute\Edit::class);

        $reflectionMethod = $reflectionClass->getMethod('generateCode');
        $reflectionMethod->setAccessible(true);

        $this->ObjectManagerInterfaceMock->expects($this->once())
            ->method('create')
            ->with('Magento\Catalog\Model\Product\Url')
            ->willReturn($this->urlMock);

        $this->urlMock->expects($this->once())
            ->method('formatUrlKey')
            ->with($label)
            ->willReturn($label);

        $result=$reflectionMethod->invoke( $this->editMock,$label);
        $this->assertEquals($expected, $result);

        $this->assertEquals($expected, $result);

    }

    /**
     * @return array
     */
    public function generateCodeUnvalidProvider()
    {
        return [
            ['attr__ny_abel', 'AnyLabel'],

        ];
    }

}

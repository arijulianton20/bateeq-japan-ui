<?php
/**
 * Customer attribute controller
 */

namespace Moonlay\CustomerAttribute\Test\Unit\Controller\Adminhtml\Attribute;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use Moonlay\CustomerAttribute\Controller\Adminhtml\Attribute\Save;


class SaveTest extends \PHPUnit\Framework\TestCase
{

    private $saveController;

    private $contextMock;

    private $coreRegistryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject | \Magento\Framework\View\Result\PageFactory
     */
    private $resultPageFactoryMock;

    private $ObjectManagerInterfaceMock;

    private $validatorFactoryMock;
    private $ValidatorMock;
    private $groupCollectionFactoryMock;

    private $customerAttributeHelperMock;

    private $layoutFactoryMock;
    private $requestInterfaceMock;
    private $redirectMock;
    private $resultFactoryMock;
    private $jasonMock;
    private $layoutMock;
    private $messageMock;
    private $ManagerInterfaceMock;
    private $AttributeMock;
    private $SessionMock;
    //need evaluate entity
    private $EntityMock;
    private $ConfigMock;
    private $SetMock;


    protected function setUp()
    {
        $this->contextMock = $this->createMock(\Magento\Backend\App\Action\Context::class);
        $this->coreRegistryMock = $this->createMock(\Magento\Framework\Registry::class);

        $this->ConfigMock = $this->getMockBuilder(\Magento\Eav\Model\Config::class)
            ->disableOriginalConstructor()
            ->setMethods(['getEntityType', 'getDefaultAttributeSetId', 'getDefaultGroupId'])
            ->getMock();

        $this->SetMock = $this->getMockBuilder(\Magento\Eav\Model\Entity\Attribute\Set::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->layoutMock = $this->createMock(\Magento\Framework\View\Layout::class);
        $this->redirectMock = $this->createMock(\Magento\Framework\Controller\Result\Redirect::class);

        $this->jasonMock = $this->createMock(\Magento\Framework\Controller\Result\Json::class);
        $this->EntityMock = $this->createMock(\Magento\Eav\Model\Entity\AbstractEntity::class);
        $this->messageMock = $this->createMock(\Magento\Framework\View\Element\Messages::class);
        $this->SessionMock = $this->getMockBuilder(\Magento\Backend\Model\Session::class)
            ->disableOriginalConstructor()
            ->setMethods(['setAttributeData'])
            ->getMock();


        $this->ObjectManagerInterfaceMock = $this->getMockBuilder(\Magento\Framework\ObjectManagerInterface::class)
            ->getMock();
        $this->AttributeMock = $this->getMockBuilder(\Magento\Customer\Model\Attribute::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->instanceNameMock = $this->getMockBuilder(\Magento\Framework\View\Result\Page::class);

        $this->resultFactoryMock = $this->createMock(\Magento\Framework\Controller\ResultFactory::class);

        $this->resultPageFactoryMock = $this->getMockBuilder(\Magento\Framework\View\Result\PageFactory::class)
            ->disableOriginalConstructor()
            //   ->setConstructorArgs([$this->objectManagerMock, $this->instanceNameMock])
            ->getMock();

        $this->validatorFactoryMock = $this->getMockBuilder(\Magento\Eav\Model\Adminhtml\System\Config\Source\Inputtype\ValidatorFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->ValidatorMock = $this->getMockBuilder(\Magento\Eav\Model\Adminhtml\System\Config\Source\Inputtype\Validator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->groupCollectionFactoryMock = $this->getMockBuilder(\Magento\Eav\Model\ResourceModel\Entity\Attribute\Group\CollectionFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->customerAttributeHelperMock = $this->getMockBuilder(\Moonlay\CustomerAttribute\Helper\Data::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->layoutFactoryMock = $this->getMockBuilder(\Magento\Framework\View\LayoutFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->ManagerInterfaceMock = $this->createMock(\Magento\Framework\Message\ManagerInterface::class);


        $this->requestInterfaceMock = $this->getMockBuilder(\Magento\Framework\App\RequestInterface::class)
            ->setMethods(['getPostValue'])
            ->getMockForAbstractClass();

        $this->contextMock->expects($this->any())
            ->method('getRequest')
            ->willReturn($this->requestInterfaceMock);

        $this->contextMock->expects($this->any())
            ->method('getResultFactory')
            ->willReturn($this->resultFactoryMock);

        $this->contextMock->expects($this->any())
            ->method('getMessageManager')
            ->willReturn($this->ManagerInterfaceMock);

        $this->contextMock->expects($this->any())
            ->method('getObjectManager')
            ->willReturn($this->ObjectManagerInterfaceMock);

        $this->contextMock->expects($this->any())
            ->method('getSession')
            ->willReturn($this->SessionMock);


        $this->objectManagerHelper = new ObjectManagerHelper($this);
        $this->saveController = $this->objectManagerHelper->getObject(
            \Moonlay\CustomerAttribute\Controller\Adminhtml\Attribute\Save::class,
            [
                'context' => $this->contextMock,
                'coreRegistry' => $this->coreRegistryMock,
                'resultPageFactory' => $this->resultPageFactoryMock,
                'validatorFactory' => $this->validatorFactoryMock,
                'groupCollectionFactory' => $this->groupCollectionFactoryMock,
                'customerAttributeHelper' => $this->customerAttributeHelperMock,
                'layoutFactory' => $this->layoutFactoryMock
            ]
        );

    }

    public function testExecuteEmptyPostValue()
    {
        $this->requestInterfaceMock->expects($this->any())
            ->method('getPostValue')
            ->willReturn([]);

        $this->resultFactoryMock->expects($this->any())
            ->method('create')
            ->with(ResultFactory::TYPE_REDIRECT)
            ->willReturn($this->redirectMock);

        $response['params'] = [];
        $response['error'] = true;

        $this->redirectMock->expects($this->any())
            ->method('setPath')
            ->with('customerattribute/*/', $response['params'])
            ->willReturnSelf();
        $this->assertInstanceOf(\Magento\Framework\Controller\Result\Redirect::class, $this->saveController->execute());

    }

    public function testExecuteRedirectInvalidAttributeCode()
    {
        $this->requestInterfaceMock->expects($this->any())
            ->method('getPostValue')
            ->willReturn([
                'attribute_code' => 'any attribute_code',
                'frontend_input' => 'any frontend end',
                'frontend_label' => 'any frontend_label'
            ]);

        $this->requestInterfaceMock->expects($this->any())
            ->method('getParam')
            ->withConsecutive(['attribute_id'],
                ['attribute_code'])
            ->willReturnOnConsecutiveCalls(
                'invalid attribute_id', 'invalid attribute_code'
            );


        $this->layoutFactoryMock->expects($this->any())
            ->method('create')
            ->willReturn($this->layoutMock);

        $this->layoutMock->expects($this->any())
            ->method('initMessages')
            ->willReturnSelf();

        $this->layoutMock->expects($this->any())
            ->method('getMessagesBlock')
            ->willReturn($this->messageMock);

        $this->messageMock->expects($this->any())
            ->method('getGroupedHtml')
            ->willReturn('any element');

        $this->resultFactoryMock->expects($this->any())
            ->method('create')
            ->with(ResultFactory::TYPE_REDIRECT)
            ->willReturn($this->redirectMock);

        $response['messages'] = ['any element'];
        $response['params'] = [
            'attribute_id' => 'invalid attribute_id',
            '_current' => true
        ];
        $response['error'] = true;

        $this->redirectMock->expects($this->any())
            ->method('setPath')
            ->with('customerattribute/*/edit', $response['params'])
            ->willReturnSelf();
        $this->assertInstanceOf(\Magento\Framework\Controller\Result\Redirect::class, $this->saveController->execute());

    }


    public function testExecuteInvalidFrontendInput()
    {
        $this->requestInterfaceMock->expects($this->any())
            ->method('getPostValue')
            ->willReturn(
                [
                    'attribute_id' => 'validattributeid',
                    'attribute_code' => 'validattributecode',
                    'frontend_input' => 'any frontend_input',
                    'frontend_label' => 'any frontendl_label'
                ]
            );

        $this->requestInterfaceMock->expects($this->any())
            ->method('getParam')
            ->withConsecutive(['attribute_id'],
                ['attribute_code'])
            ->willReturnOnConsecutiveCalls('validattributeid', 'validattributecode');

        $this->validatorFactoryMock->expects($this->any())->method('create')
            ->willReturn($this->ValidatorMock);
        $this->ValidatorMock->expects($this->any())
            ->method('isValid')
            ->with('any frontend_input')
            ->willReturn(false);

        $this->ValidatorMock->expects($this->any())
            ->method('getMessages')
            ->willReturn([
                'unvalid_frontend_input'
            ]);

        $this->resultFactoryMock->expects($this->any())
            ->method('create')
            ->with(ResultFactory::TYPE_REDIRECT)
            ->willReturn($this->redirectMock);

        $response['messages'] = ['any element'];
        $response['params'] = [
            'attribute_id' => 'validattributeid',
            '_current' => true
        ];
        $response['error'] = true;

        $this->redirectMock->expects($this->any())
            ->method('setPath')
            ->with('customerattribute/*/edit', $response['params'])
            ->willReturnSelf();

        $this->assertInstanceOf(\Magento\Framework\Controller\Result\Redirect::class, $this->saveController->execute());
    }


    public function testExecuteAttributNoExist()
    {
        $this->requestInterfaceMock->expects($this->any())
            ->method('getPostValue')
            ->willReturn(
                [
                    'attribute_id' => 'validattributeid',
                    'attribute_code' => 'validattributecode',
                    'frontend_input' => 'any frontend_input',
                    'frontend_label' => 'any frontendl_label'
                ]
            );

        $this->requestInterfaceMock->expects($this->any())
            ->method('getParam')
            ->withConsecutive(['attribute_id'],
                ['attribute_code'])
            ->willReturnOnConsecutiveCalls('validattributeid', 'validattributecode');

        $this->validatorFactoryMock->expects($this->any())->method('create')
            ->willReturn($this->ValidatorMock);
        $this->ValidatorMock->expects($this->any())
            ->method('isValid')
            ->with('any frontend_input')
            ->willReturn(true);
        $this->ObjectManagerInterfaceMock->expects($this->any())
            ->method('create')
            ->with('Magento\Customer\Model\Attribute')
            ->willReturn($this->AttributeMock);

        $this->AttributeMock->expects($this->any())->method('getId')->willReturn(false);

        $this->resultFactoryMock->expects($this->any())
            ->method('create')
            ->with(ResultFactory::TYPE_REDIRECT)
            ->willReturn($this->redirectMock);

        $response['params'] = [];
        $response['error'] = true;

        $this->redirectMock->expects($this->any())
            ->method('setPath')
            ->with('customerattribute/*/', $response['params'])
            ->willReturnSelf();

        $this->assertInstanceOf(\Magento\Framework\Controller\Result\Redirect::class, $this->saveController->execute());
    }

    public function testExecuteEntityTypeIdNotMatch()
    {
        $this->requestInterfaceMock->expects($this->any())
            ->method('getPostValue')
            ->willReturn(
                [
                    'attribute_id' => 'validattributeid',
                    'attribute_code' => 'validattributecode',
                    'frontend_input' => 'any frontend_input',
                    'frontend_label' => 'any frontendl_label'
                ]
            );

        $this->requestInterfaceMock->expects($this->any())
            ->method('getParam')
            ->withConsecutive(['attribute_id'],
                ['attribute_code'])
            ->willReturnOnConsecutiveCalls('validattributeid', 'validattributecode');

        $this->validatorFactoryMock->expects($this->any())->method('create')
            ->willReturn($this->ValidatorMock);
        $this->ValidatorMock->expects($this->any())
            ->method('isValid')
            ->with('any frontend_input')
            ->willReturn(true);
        $this->ObjectManagerInterfaceMock->expects($this->any())
            ->method('create')
            ->with('Magento\Customer\Model\Attribute')
            ->willReturn($this->AttributeMock);

        $this->AttributeMock->expects($this->any())->method('getId')->willReturn(true);

        $this->AttributeMock->expects($this->any())->method('getEntityTypeId')->willReturn(\Magento\Customer\Model\Customer::ENTITY);

        $this->SessionMock->expects($this->any())
            ->method('setAttributeData')
            ->with(
                [
                    'attribute_id' => 'validattributeid',
                    'attribute_code' => 'validattributecode',
                    'frontend_input' => 'any frontend_input',
                    'frontend_label' => 'any frontendl_label'
                ]
            )
            ->willReturnSelf();

        $this->resultFactoryMock->expects($this->any())
            ->method('create')
            ->with(ResultFactory::TYPE_REDIRECT)
            ->willReturn($this->redirectMock);

        $response['params'] = [];
        $response['error'] = true;

        $this->redirectMock->expects($this->any())
            ->method('setPath')
            ->with('customerattribute/*/', $response['params'])
            ->willReturnSelf();

        $this->assertInstanceOf(\Magento\Framework\Controller\Result\Redirect::class, $this->saveController->execute());
    }

    public function testExecuteEntityTypeIdMatch()
    {
        $this->requestInterfaceMock->expects($this->any())
            ->method('getPostValue')
            ->willReturn(
                [
                    'attribute_id' => 'validattributeid',
                    'attribute_code' => 'validattributecode',
                    'frontend_input' => 'any frontend_input',
                    'frontend_label' => 'any frontendl_label'
                ]
            );

        $this->requestInterfaceMock->expects($this->any())
            ->method('getParam')
            ->withConsecutive(['attribute_id'],
                ['attribute_code'])
            ->willReturnOnConsecutiveCalls('validattributeid', 'validattributecode');

        $this->validatorFactoryMock->expects($this->any())->method('create')
            ->willReturn($this->ValidatorMock);
        $this->ValidatorMock->expects($this->any())
            ->method('isValid')
            ->with('any frontend_input')
            ->willReturn(true);
        $this->ObjectManagerInterfaceMock->expects($this->any())
            ->method('create')
            ->with('Magento\Customer\Model\Attribute')
            ->willReturn($this->AttributeMock);

        $this->AttributeMock->expects($this->any())->method('getId')->willReturn(true);

        $this->AttributeMock->expects($this->any())->method('getEntityTypeId')->willReturn(false);

        $this->ObjectManagerInterfaceMock->expects($this->any())
            ->method('get')
            ->withConsecutive(
                ['Magento\Eav\Model\Config'],
                ['Magento\Eav\Model\Entity\Attribute\Set']
            )
            ->willReturnOnConsecutiveCalls($this->ConfigMock, $this->SetMock);

        $this->ConfigMock->expects($this->any())->method('getEntityType')
            ->with(\Magento\Customer\Model\Customer::ENTITY)
            ->willReturnSelf();

        $this->resultFactoryMock->expects($this->any())
            ->method('create')
            ->with(ResultFactory::TYPE_REDIRECT)
            ->willReturn($this->redirectMock);

        $response['params'] = [];
        $response['error'] = false;

        $this->redirectMock->expects($this->any())
            ->method('setPath')
            ->with('customerattribute/*/', $response['params'])
            ->willReturnSelf();

        $this->assertInstanceOf(\Magento\Framework\Controller\Result\Redirect::class, $this->saveController->execute());
    }


    public function testExecuteAttributeIdNotSet()
    {
        $this->requestInterfaceMock->expects($this->any())
            ->method('getPostValue')
            ->willReturn(
                [
                    'attribute_id' => null,
                    'attribute_code' => 'validattributecode',
                    'frontend_input' => 'any frontend_input',
                    'frontend_label' => 'any frontendl_label'
                ]
            );

        $this->requestInterfaceMock->expects($this->any())
            ->method('getParam')
            ->withConsecutive(['attribute_id'],
                ['attribute_code'])
            ->willReturnOnConsecutiveCalls(null, 'validattributecode');

        $this->validatorFactoryMock->expects($this->any())->method('create')
            ->willReturn($this->ValidatorMock);
        $this->ValidatorMock->expects($this->any())
            ->method('isValid')
            ->with('any frontend_input')
            ->willReturn(true);
        $this->ObjectManagerInterfaceMock->expects($this->any())
            ->method('create')
            ->with('Magento\Customer\Model\Attribute')
            ->willReturn($this->AttributeMock);

        $this->AttributeMock->expects($this->any())->method('getId')->willReturn(true);

        $this->AttributeMock->expects($this->any())->method('getEntityTypeId')->willReturn(false);

        $this->ObjectManagerInterfaceMock->expects($this->any())
            ->method('get')
            ->withConsecutive(
                ['Magento\Eav\Model\Config'],
                ['Magento\Eav\Model\Entity\Attribute\Set']
            )
            ->willReturnOnConsecutiveCalls($this->ConfigMock, $this->SetMock);

        $this->ConfigMock->expects($this->any())->method('getEntityType')
            ->with(\Magento\Customer\Model\Customer::ENTITY)
            ->willReturnSelf();

        $this->resultFactoryMock->expects($this->any())
            ->method('create')
            ->with(ResultFactory::TYPE_REDIRECT)
            ->willReturn($this->redirectMock);

        $response['params'] = [];
        $response['error'] = false;

        $this->redirectMock->expects($this->any())
            ->method('setPath')
            ->with('customerattribute/*/', $response['params'])
            ->willReturnSelf();

        $this->assertInstanceOf(\Magento\Framework\Controller\Result\Redirect::class, $this->saveController->execute());
    }

    public function testExecuteSuccesSavedAttribute()
    {
        $this->requestInterfaceMock->expects($this->any())
            ->method('getPostValue')
            ->willReturn(
                [
                    'attribute_id' => 'validattributeid',
                    'attribute_code' => 'validattributecode',
                    'frontend_input' => 'any frontend_input',
                    'frontend_label' => 'any frontendl_label'
                ]
            );

        $this->requestInterfaceMock->expects($this->any())
            ->method('getParam')
            ->withConsecutive(['attribute_id'],
                ['attribute_code'])
            ->willReturnOnConsecutiveCalls('validattributeid', 'validattributecode');

        $this->validatorFactoryMock->expects($this->any())->method('create')
            ->willReturn($this->ValidatorMock);
        $this->ValidatorMock->expects($this->any())
            ->method('isValid')
            ->with('any frontend_input')
            ->willReturn(true);
        $this->ObjectManagerInterfaceMock->expects($this->any())
            ->method('create')
            ->with('Magento\Customer\Model\Attribute')
            ->willReturn($this->AttributeMock);

        $this->AttributeMock->expects($this->any())->method('getId')->willReturn(true);

        $this->AttributeMock->expects($this->any())->method('getEntityTypeId')->willReturn(false);

        $this->ObjectManagerInterfaceMock->expects($this->any())
            ->method('get')
            ->withConsecutive(
                ['Magento\Eav\Model\Config'],
                ['Magento\Eav\Model\Entity\Attribute\Set']
            )
            ->willReturnOnConsecutiveCalls($this->ConfigMock, $this->SetMock);

        $this->ConfigMock->expects($this->any())->method('getEntityType')
            ->with(\Magento\Customer\Model\Customer::ENTITY)
            ->willReturnSelf();

        $this->resultFactoryMock->expects($this->any())
            ->method('create')
            ->with(ResultFactory::TYPE_REDIRECT)
            ->willReturn($this->redirectMock);

        $response['params'] = [];
        $response['error'] = false;

        $this->redirectMock->expects($this->any())
            ->method('setPath')
            ->with('customerattribute/*/', $response['params'])
            ->willReturnSelf();

        $this->assertInstanceOf(\Magento\Framework\Controller\Result\Redirect::class, $this->saveController->execute());
    }

    public function testExecuteSuccesSaveEditAttribute()
    {
        $this->requestInterfaceMock->expects($this->any())
            ->method('getPostValue')
            ->willReturn(
                [
                    'attribute_id' => 'validattributeid',
                    'attribute_code' => 'validattributecode',
                    'frontend_input' => null,
                    'frontend_label' => 'any frontendl_label',
                    'default_value' => 'default_value_text',
                    'customer_account_create' => 1,
                    'customer_account_edit' => 1 ,
                    'adminhtml_customer' => 1,

                ]
            );

        $this->requestInterfaceMock->expects($this->any())
            ->method('getParam')
            ->withConsecutive(['attribute_id'],
                ['attribute_code'],['default_value_text'], ['back', false])
            ->willReturnOnConsecutiveCalls('validattributeid', 'validattributecode','default_value_text', true);

        $this->validatorFactoryMock->expects($this->any())->method('create')
            ->willReturn($this->ValidatorMock);
        $this->ValidatorMock->expects($this->any())
            ->method('isValid')
            ->with('any frontend_input')
            ->willReturn(true);
        $this->ObjectManagerInterfaceMock->expects($this->any())
            ->method('create')
            ->with('Magento\Customer\Model\Attribute')
            ->willReturn($this->AttributeMock);

        $this->AttributeMock->expects($this->any())->method('getId')->willReturn(true);

        $this->AttributeMock->expects($this->any())->method('getEntityTypeId')->willReturn(false);

        $this->AttributeMock->expects($this->any())->method('getDefaultValueByInput')
            ->with(null)
            ->willReturn('default_value_text');

        $this->ObjectManagerInterfaceMock->expects($this->any())
            ->method('get')
            ->withConsecutive(
                ['Magento\Eav\Model\Config'],
                ['Magento\Eav\Model\Entity\Attribute\Set']
            )
            ->willReturnOnConsecutiveCalls($this->ConfigMock, $this->SetMock);

        $this->ConfigMock->expects($this->any())->method('getEntityType')
            ->with(\Magento\Customer\Model\Customer::ENTITY)
            ->willReturnSelf();

        $this->SessionMock->expects($this->any())->method('setAttributeData')
            ->withConsecutive([false], [])
            ->willReturnOnConsecutiveCalls(
                $this->SessionMock,
                $this->SessionMock);

        $this->resultFactoryMock->expects($this->any())
            ->method('create')
            ->with(ResultFactory::TYPE_REDIRECT)
            ->willReturn($this->redirectMock);

        $response['params'] = [
            'attribute_id' => 'validattributeid',
            '_current' => true

        ];
        $response['error'] = false;

        $this->redirectMock->expects($this->any())
            ->method('setPath')
            ->with('customerattribute/*/edit', $response['params'])
            ->willReturnSelf();

        $this->assertInstanceOf(\Magento\Framework\Controller\Result\Redirect::class, $this->saveController->execute());
    }

    public function testExecuteThrowException()
    {
        $this->requestInterfaceMock->expects($this->any())
            ->method('getPostValue')
            ->willReturn(
                [
                    'attribute_id' => 'validattributeid',
                    'attribute_code' => 'validattributecode',
                    'frontend_input' => 'any frontend_input',
                    'frontend_label' => 'any frontendl_label',
                    'default_value' => 'default_value_text',
                    'customer_account_create' => 1,
                    'customer_account_edit' => 1 ,
                    'adminhtml_customer' => 1,
                ]
            );

        $this->requestInterfaceMock->expects($this->any())
            ->method('getParam')
            ->withConsecutive(['attribute_id'],
                ['attribute_code'])
            ->willReturnOnConsecutiveCalls('validattributeid', 'validattributecode');

        $this->validatorFactoryMock->expects($this->any())->method('create')
            ->willReturn($this->ValidatorMock);
        $this->ValidatorMock->expects($this->any())
            ->method('isValid')
            ->with('any frontend_input')
            ->willReturn(true);
        $this->ObjectManagerInterfaceMock->expects($this->any())
            ->method('create')
            ->with('Magento\Customer\Model\Attribute')
            ->willReturn($this->AttributeMock);

        $this->AttributeMock->expects($this->any())->method('getId')->willReturn(true);

        $this->AttributeMock->expects($this->any())->method('getEntityTypeId')->willReturn(false);

        $this->ObjectManagerInterfaceMock->expects($this->any())
            ->method('get')
            ->withConsecutive(
                ['Magento\Eav\Model\Config'],
                ['Magento\Eav\Model\Entity\Attribute\Set']
            )
            ->willReturnOnConsecutiveCalls($this->ConfigMock, $this->SetMock);

        $this->ConfigMock->expects($this->any())->method('getEntityType')
            ->with(\Magento\Customer\Model\Customer::ENTITY)
            ->willReturnSelf();

        $this->SessionMock->expects($this->any())->method('setAttributeData')
            ->withConsecutive([false], [])
            ->willReturnOnConsecutiveCalls(
                $this->throwException(
                    new \Exception()
                ),
                $this->SessionMock
            );

        $this->resultFactoryMock->expects($this->any())
            ->method('create')
            ->with(ResultFactory::TYPE_REDIRECT)
            ->willReturn($this->redirectMock);

        $response['params'] = [
            'attribute_id' => 'validattributeid',
            '_current' => true
        ];
        $response['error'] = true;

        $this->redirectMock->expects($this->any())
            ->method('setPath')
            ->with('customerattribute/*/edit', $response['params'])
            ->willReturnSelf();

        $this->assertInstanceOf(\Magento\Framework\Controller\Result\Redirect::class, $this->saveController->execute());
    }


    public function testRedirect()
    {

        $this->resultFactoryMock->expects($this->any())
            ->method('create')
            ->with('redirect')
            ->willReturn($this->redirectMock);

        $this->redirectMock->expects($this->any())
            ->method('setPath')
            ->with([], [])
            ->willReturnSelf();


        $refClass = new \ReflectionClass(\Moonlay\CustomerAttribute\Controller\Adminhtml\Attribute\Save::class);
        $returnResult = $refClass->getMethod('returnResult');
        $returnResult->setAccessible(true);
        $this->assertInstanceOf(\Magento\Framework\Controller\Result\Redirect::class, $returnResult->invoke($this->saveController, []));

    }

    public function testResultJason()
    {
        $this->requestInterfaceMock->expects($this->any())
            ->method('getParam')
            ->with('isAjax')
            ->willReturn(true);

        $this->layoutFactoryMock->expects($this->any())
            ->method('create')
            ->willReturn($this->layoutMock);

        $this->layoutMock->expects($this->any())
            ->method('initMessages')
            ->willReturnSelf();

        $this->layoutMock->expects($this->any())
            ->method('getMessagesBlock')
            ->willReturn($this->messageMock);

        $this->resultFactoryMock->expects($this->any())
            ->method('create')
            ->with('json')
            ->willReturn($this->jasonMock);

        $response['messages'] = [null];
        $response['params'] = ['attribute_id' => 'any attribut id', '_current' => true];
        $response['error'] = true;

        $this->jasonMock->expects($this->any())
            ->method('setData')
            ->with($response)
            ->willReturnSelf();

        $refClass = new \ReflectionClass(\Moonlay\CustomerAttribute\Controller\Adminhtml\Attribute\Save::class);
        $returnJason = $refClass->getMethod('returnResult');
        $returnJason->setAccessible(true);
        $this->assertInstanceOf(\Magento\Framework\Controller\Result\Json::class, $returnJason->invoke($this->saveController,
            'customerattribute/*/edit',
            ['attribute_id' => 'any attribut id', '_current' => true],
            ['error' => true]
        ));
    }

    public function testIsAjax()
    {
        $this->requestInterfaceMock->expects($this->any())
            ->method('getParam')
            ->with('isAjax')
            ->willReturn(true);

        $refClass = new \ReflectionClass(\Moonlay\CustomerAttribute\Controller\Adminhtml\Attribute\Save::class);
        $isAjax = $refClass->getMethod('isAjax');
        $isAjax->setAccessible(true);
        $this->assertEquals(true, $isAjax->invoke($this->saveController, []));
    }


}




//tampungan code
//        $this->ManagerInterfaceMock->expects($this->any())
//            ->method('addError')
//            ->with( __(
//                'Attribute code "%1" is invalid. Please use only letters (a-z), ' .
//                'numbers (0-9) or underscore(_) in this field, first character should be a letter.',
//                'any attribute_code'
//            ))
//            ->willReturnSelf();
//
//        $this->layoutFactoryMock->expects($this->any())
//            ->method('create')
//            ->willReturn($this->layoutMock);
//
//        $this->layoutMock->expects($this->any())
//            ->method('initMessages')
//            ->willReturnSelf();
//
//        $this->layoutMock->expects($this->any())
//            ->method('getMessagesBlock')
//            ->willReturn($this->messageMock);
//
//       $this->messageMock->expects($this->any())
//            ->method('getGroupedHtml')
//            ->willReturn('any element');
//
//        $this->resultFactoryMock->expects($this->any())
//            ->method('create')
//            ->with('json')
//            ->willReturn($this->jasonMock);
//
//        $response['messages'] = ['any element'];
//        $response['params'] = [
//            'attribute_id' => 'any attribute_code',
//            '_current' => true
//        ];
//        $response['error'] =true;
//
//            $this->jasonMock->expects($this->any())
//            ->method('setData')
//            ->with($response)
//            ->willReturnSelf();
//
//        $this->saveController->execute();
//    }
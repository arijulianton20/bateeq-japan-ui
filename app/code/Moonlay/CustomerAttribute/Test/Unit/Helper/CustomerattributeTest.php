<?php
/**
 * Customer attribute controller
 */

namespace Moonlay\CustomerAttribute\Test\Unit\Helper;


use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use Moonlay\CustomerAttribute\Helper\Customerattribute;


class CustomerattributeTest extends \PHPUnit\Framework\TestCase
{

    private $customerAttributeMock;

    private $ContextMock;

    private $AttributeFactoryMock;

    private $attributeFactoryData;

    private $EntityFactoryMock;

    private $ConfigFactoryMock;

    private $customerFactoryMock;

    private $StoreManagerInterfaceMock;

    protected function setUp()
    {
        parent::setUp();

        $this->ContextMock = $this->createMock(\Magento\Framework\App\Helper\Context::class);
        $this->AttributeFactoryMock = $this->getMockBuilder(\Magento\Customer\Model\AttributeFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create', 'setEntityTypeId', 'getCollection', 'addVisibleFilter', 'addFieldToFilter', 'setOrder'])
            ->getMock();

//        Magento\Eav\Model\Entity\Attribute\AbstractAttribute

        $this->EntityFactoryMock = $this->getMockBuilder(\Magento\Eav\Model\EntityFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create', 'setType', 'getTypeId'])
            ->getMock();

        $this->ConfigFactoryMock = $this->getMockBuilder(\Magento\Eav\Model\ConfigFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create', 'getAttribute', 'getSource', 'getAllOptions', 'getUsedInForms'])
            ->getMock();


        $this->customerFactoryMock = $this->getMockBuilder(\Magento\Customer\Model\CustomerFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create', 'load'])
            ->getMock();

        $this->StoreManagerInterfaceMock = $this->getMockBuilder(\Magento\Store\Model\StoreManagerInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getStore', 'getStoreId'])
            ->getMockForAbstractClass();

        $this->objectManagerHelper = new ObjectManagerHelper($this);
        $this->customerAttributeMock = $this->objectManagerHelper->getObject(
            \Moonlay\CustomerAttribute\Helper\Customerattribute::class,
            [
                'context' => $this->ContextMock,
                'attributeFactory' => $this->AttributeFactoryMock,
                'customerEntityFactory' => $this->EntityFactoryMock,
                'eavAttributeFactory' => $this->ConfigFactoryMock,
                'customerFactory' => $this->customerFactoryMock,
                'storeManager' => $this->StoreManagerInterfaceMock
            ]
        );


    }

    public function testGetUserDefinedAttribures()
    {
        $entityTypeId = 1;

        $this->EntityFactoryMock->expects($this->any())
            ->method('create')
            ->willReturnSelf();

        $this->EntityFactoryMock->expects($this->any())
            ->method('setType')
            ->with(\Magento\Customer\Model\Customer::ENTITY)
            ->willReturnSelf();

        $this->EntityFactoryMock->expects($this->any())
            ->method('getTypeId')
            ->willReturn($entityTypeId);


        $this->AttributeFactoryMock->expects($this->any())
            ->method('create')
            ->willReturnSelf();

        $this->AttributeFactoryMock->expects($this->any())
            ->method('setEntityTypeId')
            ->with($entityTypeId)
            ->willReturn($this->AttributeFactoryMock);

        $this->AttributeFactoryMock->expects($this->any())
            ->method('getCollection')
            ->willReturnSelf();

        $this->AttributeFactoryMock->expects($this->any())
            ->method('addVisibleFilter')
            ->willReturnSelf();

        $this->AttributeFactoryMock->expects($this->any())
            ->method('addFieldToFilter')
            ->with('is_user_defined', 1)
            ->willReturnSelf();

        $this->AttributeFactoryMock->expects($this->any())
            ->method('setOrder')
            ->with('sort_order', 'ASC')
            ->willReturnSelf();

        $this->assertSame($this->AttributeFactoryMock, $this->customerAttributeMock->getUserDefinedAttribures());
        $this->assertInstanceOf(\Magento\Customer\Model\AttributeFactory::class, $this->customerAttributeMock->getUserDefinedAttribures());

    }

    public function testIsAttribureForCustomerAccountCreateSucces()
    {
        $attributeCode = 'any attribut code';
        $this->ConfigFactoryMock->expects($this->any())->method('create')->willReturnSelf();
        $this->ConfigFactoryMock->expects($this->any())
            ->method('getAttribute')
            ->with('customer', $attributeCode)
            ->willReturnSelf();
        $this->ConfigFactoryMock->expects($this->any())->method('getUsedInForms')->willReturn(['customer_account_create']);

        $this->assertEquals(true, $this->customerAttributeMock->isAttribureForCustomerAccountCreate('any attribut code'));
    }

    public function testIsAttribureForCustomerAccountCreateFail()
    {
        $attributeCode = 'any attribut code';
        $this->ConfigFactoryMock->expects($this->any())->method('create')->willReturnSelf();
        $this->ConfigFactoryMock->expects($this->any())
            ->method('getAttribute')
            ->with('customer', $attributeCode)
            ->willReturnSelf();
        $this->ConfigFactoryMock->expects($this->any())->method('getUsedInForms')->willReturn(['no_customer_account_create']);

        $this->assertEquals(false, $this->customerAttributeMock->isAttribureForCustomerAccountCreate('any attribut code'));
    }

    public function testIsAttribureForCustomerAccountEditSucces(){
        $attributeCode = 'any attribut code';
        $this->ConfigFactoryMock->expects($this->any())->method('create')->willReturnSelf();
        $this->ConfigFactoryMock->expects($this->any())
            ->method('getAttribute')
            ->with('customer', $attributeCode)
            ->willReturnSelf();
        $this->ConfigFactoryMock->expects($this->any())->method('getUsedInForms')->willReturn(['customer_account_edit']);

        $this->assertEquals(true, $this->customerAttributeMock->isAttribureForCustomerAccountEdit('any attribut code'));
    }

    public function testIsAttribureForCustomerAccountEditFail(){
        $attributeCode = 'any attribut code';
        $this->ConfigFactoryMock->expects($this->any())->method('create')->willReturnSelf();
        $this->ConfigFactoryMock->expects($this->any())
            ->method('getAttribute')
            ->with('customer', $attributeCode)
            ->willReturnSelf();
        $this->ConfigFactoryMock->expects($this->any())->method('getUsedInForms')->willReturn(['no_customer_account_edit']);

        $this->assertEquals(false, $this->customerAttributeMock->isAttribureForCustomerAccountEdit('any attribut code'));
    }

    public function testgGetStoreId()
    {

        $this->StoreManagerInterfaceMock->expects($this->once())
            ->method('getStore')
            ->willReturnSelf();

        $this->StoreManagerInterfaceMock->expects($this->once())
            ->method('getStoreId')
            ->willReturn(1);

        $this->assertEquals(1, $this->customerAttributeMock->getStoreId());
    }

    public function testGetAttributeOptions()
    {
        $attributeCode = 'any code';
        $customerEntity = \Magento\Customer\Model\Customer::ENTITY;

        $this->ConfigFactoryMock->expects($this->once())
            ->method('create')
            ->willReturnSelf();

        $this->ConfigFactoryMock->expects($this->once())
            ->method('getAttribute')
            ->with($customerEntity, $attributeCode)
            ->willReturnSelf();

        $this->ConfigFactoryMock->expects($this->once())
            ->method('getSource')
            ->willReturnSelf();

        $this->ConfigFactoryMock->expects($this->once())
            ->method('getAllOptions')
            ->willReturnSelf();

        $this->customerAttributeMock->getAttributeOptions($attributeCode);

    }

    public function testGetCustomer()
    {
        $customerId = 1;

        $this->customerFactoryMock->expects($this->once())
            ->method('create')
            ->willReturnSelf();

        $this->customerFactoryMock->expects($this->once())
            ->method('load')
            ->with($customerId)
            ->willReturnSelf();

        $this->customerAttributeMock->getCustomer($customerId);


    }

}
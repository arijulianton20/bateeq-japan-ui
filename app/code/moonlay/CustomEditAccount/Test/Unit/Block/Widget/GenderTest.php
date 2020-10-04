<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

// @codingStandardsIgnoreFile

namespace Moonlay\CustomEditAccount\Test\Unit\Block\Widget;

use Moonlay\CustomEditAccount\Block\Widget\Gender;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Exception\NoSuchEntityException;



class GenderTest extends \PHPUnit\Framework\TestCase
{
    /** Constants used in the unit tests */
    const CUSTOMER_ENTITY_TYPE = 'customer';

    const GENDER_ATTRIBUTE_CODE = 'gender';

    const PREFIX_ATTRIBUTE_CODE = 'prefix';

    const INVALID_ATTRIBUTE_CODE = 'invalid attribute code';

    const PREFIX_STORE_LABEL = 'Name Prefix';

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject | \Magento\Customer\Api\CustomerMetadataInterface
     */
    private $customerMetadata;

    /** @var \PHPUnit_Framework_MockObject_MockObject | \Magento\Customer\Api\Data\AttributeMetadataInterface */
    private $attribute;

    /** @var \PHPUnit_Framework_MockObject_MockObject | \Magento\Customer\Model\Session */
    private $customerSession;

    /** @var \PHPUnit_Framework_MockObject_MockObject | \Magento\Customer\Api\CustomerRepositoryInterface */
    private $customerRepository;

    /** @var Gender */
    private $block;

    protected function setUp()
    {
        $this->attribute = $this->getMockBuilder(\Magento\Customer\Api\Data\AttributeMetadataInterface::class)
            ->getMockForAbstractClass();

          

        $this->customerMetadata = $this->getMockBuilder(\Magento\Customer\Api\CustomerMetadataInterface::class)
            ->getMockForAbstractClass();
        $this->customerMetadata->expects($this->any())
            ->method('getAttributeMetadata')
            //->with(self::GENDER_ATTRIBUTE_CODE)
            ->will($this->returnValue($this->attribute));

        $this->customerRepository = $this
            ->getMockBuilder(\Magento\Customer\Api\CustomerRepositoryInterface::class)
            ->getMockForAbstractClass();
        $this->customerSession = $this->createMock(\Magento\Customer\Model\Session::class);

        $this->block = new \Moonlay\CustomEditAccount\Block\Widget\Gender(
            $this->createMock(\Magento\Framework\View\Element\Template\Context::class),
            $this->createMock(\Magento\Customer\Helper\Address::class),
            $this->customerMetadata,
            $this->customerRepository,
            $this->customerSession
        );
    }

    /**
     * Test the Gender::isEnabled() method.
     *
     * @param bool $isVisible Determines whether the 'gender' attribute is visible or enabled
     * @param bool $expectedValue The value we expect from Gender::isEnabled()
     * @return void
     *
     * @dataProvider isEnabledDataProvider
     */
    public function testIsEnabled($isVisible, $expectedValue)
    {
        $this->attribute->expects($this->once())->method('isVisible')->will($this->returnValue($isVisible));
        $this->assertSame($expectedValue, $this->block->isEnabled());
    }

    /**
     * The testIsEnabled data provider.
     * @return array
     */
    public function isEnabledDataProvider()
    {
        return [[true, true], [false, false]];
    }

    public function testIsEnabledWithException()
    {
        
        $this->customerMetadata->expects(
            $this->any()
        )->method(
            'getAttributeMetadata'
        )->will(
            $this->throwException(new NoSuchEntityException(
                __(
                    'No such entity with %fieldName = %fieldValue',
                    ['fieldName' => 'field', 'fieldValue' => 'value']
                )
            ))
        );
        $this->assertSame(false, $this->block->isEnabled());
    }

    /**
     * Test the Gender::isRequired() method.
     *
     * @param bool $isRequired Determines whether the 'gender' attribute is required
     * @param bool $expectedValue The value we expect from Gender::isRequired()
     * @return void
     *
     * @dataProvider isRequiredDataProvider
     */
    public function testIsRequired($isRequired, $expectedValue)
    {
        $this->attribute->expects($this->once())->method('isRequired')->will($this->returnValue($isRequired));
        $this->assertSame($expectedValue, $this->block->isRequired());
    }

    /**
     * The testIsRequired data provider.
     * @return array
     */
    public function isRequiredDataProvider()
    {
        return [[true, true], [false, false]];
    }

    public function testIsRequiredWithException()
    {
        $this->customerMetadata->expects(
            $this->any()
        )->method(
            'getAttributeMetadata'
        )->will(
            $this->throwException(new NoSuchEntityException(
                __(
                    'No such entity with %fieldName = %fieldValue',
                    ['fieldName' => 'field', 'fieldValue' => 'value']
                )
            ))
        );
        $this->assertSame(false, $this->block->isRequired());
    }


/**
     * @param string $attributeCode An attribute code
     * @param string $storeLabel The attribute's store label
     * @param string $expectedValue The expected value of Gender::getStoreLabel()
     *
     * @dataProvider getStoreLabelProvider
     */
    public function testGetStoreLabel($attributeCode, $storeLabel, $expectedValue)
    {

        $this->attribute->expects($this->atLeastOnce())->method('getStoreLabel')->willReturn($storeLabel);
        $this->assertEquals($expectedValue, $this->block->getStoreLabel($attributeCode));
    }

    /**
     * This data provider provides two data sets. One tests that an empty string is returned for an invalid
     * attribute code instead of an exception being thrown. The second tests that the correct store label is
     * returned for a valid attribute code.
     *
     * @return array
     */
    public function getStoreLabelProvider()
    {
        return [
            [self::INVALID_ATTRIBUTE_CODE, '', ''],
            [self::PREFIX_ATTRIBUTE_CODE, self::PREFIX_STORE_LABEL, self::PREFIX_STORE_LABEL]
        ];
    }


    


    /**
     * Test the Gender::getCustomer() method.
     * @return void
     */
    public function testGetCustomer()
    {
        $customerData = $this->getMockBuilder(\Magento\Customer\Api\Data\CustomerInterface::class)
            ->getMockForAbstractClass();
        $this->customerSession->expects($this->once())->method('getCustomerId')->will($this->returnValue(1));
        $this->customerRepository
            ->expects($this->once())
            ->method('getById')
            ->with(1)
            ->will($this->returnValue($customerData));

        $customer = $this->block->getCustomer();
        $this->assertSame($customerData, $customer);
    }

    /**
     * Test the Gender::getGenderOptions() method.
     * @return void
     */
    public function testGetGenderOptions()
    {
        $options = [
            ['label' => __('Male'), 'value' => 'M'],
            ['label' => __('Female'), 'value' => 'F'],
            ['label' => __('Not Specified'), 'value' => 'NA']
        ];

        $this->attribute->expects($this->once())->method('getOptions')->will($this->returnValue($options));
        $this->assertSame($options, $this->block->getGenderOptions());
    }
}

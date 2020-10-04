<?php
/**
 * Customer attribute controller
 */

namespace Moonlay\CustomerAttribute\Test\Unit\Helper\DataTest;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use Moonlay\CustomerAttribute\Helper\Customerattribute;

class DataTest extends \PHPUnit\Framework\TestCase
{
    private $dataMock;

    protected function setUp()
    {
        $this->objectManagerHelper = new ObjectManagerHelper($this);
        $this->dataMock = $this->objectManagerHelper->getObject(
            \Moonlay\CustomerAttribute\Helper\Data::class,
            [
            ]
        );
    }

    public function testGetAttributeInputTypesNull()
    {
        $this->dataMock->getAttributeInputTypes('');
        $this->assertNull(null, $this->dataMock->getAttributeInputTypes());
    }

    public function testGetAttributeInputTypes()
    {
        $result = $this->dataMock->getAttributeInputTypes('multiselect');

        $expectation = ['backend_model' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
            'source_model' => 'Magento\Eav\Model\Entity\Attribute\Source\Table'];
        $this->assertEquals($expectation, $result);

    }

    public function testGetAttributeBackendModelByInputType()
    {
        $result = $this->dataMock->getAttributeBackendModelByInputType('multiselect');
        $this->assertEquals('Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend', $result);
    }

    public function testGetAttributeBackendModelByInputTypeNull()
    {
        $inputType = '';
        $result = $this->dataMock->getAttributeBackendModelByInputType($inputType);
        $this->assertEquals(null, $result);
    }

    public function testGetAttributeSourceModelByInputType()
    {
        $result = $this->dataMock->getAttributeSourceModelByInputType('multiselect');
        $this->assertEquals('Magento\Eav\Model\Entity\Attribute\Source\Table', $result);
    }


    public function testGetAttributeSourceModelByInputTypeNull()
    {
        $inputType = null;
        $result = $this->dataMock->getAttributeSourceModelByInputType($inputType);
        $this->assertEquals(null, $result);
    }


}

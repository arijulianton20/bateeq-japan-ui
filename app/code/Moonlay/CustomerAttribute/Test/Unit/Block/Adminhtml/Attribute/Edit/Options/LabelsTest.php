<?php

namespace Moonlay\CustomerAttribute\Test\Unit\Block\Adminhtml\Attribute\Edit\Options;

use Magento\Eav\Block\Adminhtml\Attribute\Edit\Options\Labels;

class LabelsTest extends \PHPUnit\Framework\TestCase
{
     /** @var Delete */
     private $block;

     private $_registry;

     private $context;

    protected function setUp(){

        $this->context = $this->createMock(\Magento\Backend\Block\Template\Context::class);
        $this->_registry = $this->createMock(\Magento\Framework\Registry::class);

        // \Magento\Backend\Block\Template\Context $context,
        // \Magento\Framework\Registry $registry,
        $this->block = new Labels($this->context, $this->_registry);


    }
    
 /**
     * @param string $key
     * @param string|null $expectedValue
     *
     * @dataProvider getConfigAttributeObjectProvider
     */

    public function testGetAttributeObject($key,$expectedValue){
 
    $this->_registry->expects(
                $this->any()
            )->method(
                'registry'
            )->with(
                $key
            )->will(
                $this->returnValue($expectedValue)
            );
            $this->assertEquals($expectedValue, $this->block->getAttributeObject());
        }

         /**
     * @return array
     */
    public function getConfigAttributeObjectProvider()
    {
        return [[null, null]];
    }

    
}
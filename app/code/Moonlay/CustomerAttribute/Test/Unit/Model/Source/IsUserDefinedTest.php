<?php
/**
 * Customer attribute controller
 */
namespace Moonlay\CustomerAttribute\Test\Unit\Model\Source;

use Moonlay\CustomerAttribute\Model\Source\IsUserDefined;

class IsUserDefinedTest extends \PHPUnit\Framework\TestCase
{
    private $block;

    protected function setUp()
    {
        $this->block = new IsUserDefined();

    }

    public function testToOptionArray(){

        $this->assertInternalType('array', $this->block->toOptionArray());
    }
}
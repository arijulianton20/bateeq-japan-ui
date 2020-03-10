<?php
namespace Moonlay\CustomHome\Test\Unit;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;

class registrationTest extends \PHPUnit\Framework\TestCase
{
    private $compRegistrarObj;

    protected function setUp()
    {
        $this->objectManagerHelper = new ObjectManagerHelper($this);
        $this->compRegistrarObj = $this->objectManagerHelper->getObject(
            \Magento\Framework\Component\ComponentRegistrar::class,
            [

            ]
        );

    }

    public function testCoba()
    {


    }



}
<?php

namespace Moonlay\CustomHome\Test\Unit\Plugin\Checkout\Block;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use Moonlay\CustomHome\Plugin\Checkout\Block\LayoutProcessor;
use PHPUnit\Framework\TestCase;

class LayoutProcessorTest extends TestCase
{
    private $objectManagerHelper;
    private $layoutProcessorObj;
private $subjectMock;


    protected function setUp()
    {
        $this->subjectMock = $this->getMockBuilder(\Magento\Checkout\Block\Checkout\LayoutProcessor::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->objectManagerHelper = new ObjectManagerHelper($this);
        $this->layoutProcessorObj = $this->objectManagerHelper->getObject(
            LayoutProcessor::class,
            [
            ]
        );
    }
    public function testAfterProcess(){
       $result= $this->layoutProcessorObj->afterProcess($this->subjectMock,[]);

        $expected = [
            'components'=>[
                'checkout'=>[
                    'children'=>[
                        'steps'=>[
                            'children'=>[
                                'shipping-step'=>[
                                    'children'=>[
                                        'shippingAddress'=>[
                                            'children'=>[
                                                'shipping-address-fieldset'=>[
                                                    'children'=>[
                                                        'street'=>[
                                                            'sortOrder'=>74
                                                        ]
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $this->assertEquals($expected,$result);
    }
}

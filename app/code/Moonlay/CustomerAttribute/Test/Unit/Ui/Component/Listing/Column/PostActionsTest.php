<?php
/**
 * Customer attribute controller
 */

namespace Moonlay\CustomerAttribute\Test\Unit\Ui\Component\Listing\Column;

use Moonlay\CustomerAttribute\Ui\Component\Listing\Column\PostActions;

class PostActionsTest extends \PHPUnit\Framework\TestCase
{
    private $block;
    private $context;
    private $uiComponentFactory;
    private $urlBuilder;
    private $components = [];
    private $data = [];

    protected function setUp()
    {
        $this->context = $this->getMockBuilder(\Magento\Framework\View\Element\UiComponent\ContextInterface::class)
            ->getMockForAbstractClass();

        $this->uiComponentFactory = $this->getMockBuilder(\Magento\Framework\View\Element\UiComponentFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->urlBuilder = $this->getMockBuilder(\Magento\Framework\UrlInterface::class)
            ->getMockForAbstractClass();


        $this->block = new \Moonlay\CustomerAttribute\Ui\Component\Listing\Column\PostActions(
            $this->context,
            $this->uiComponentFactory,
            $this->urlBuilder,
            $this->components,
            $this->data
        );

    }

    public function testPrepareDataSourceEmpty()
    {

        $this->assertEmpty($this->block->prepareDataSource([]));
    }

    public function testPrepareDataSource()
    {

        $dataSource = [
            'data' => [
                'items' => [
                    [

                        'attribute_id' => 1,
                    ]
                ]
            ]
        ];


        $this->block->prepareDataSource($dataSource);
        // $this->assertEquals($expectedDataSource, $this->block->prepareDataSource($dataSource));
        //   $this->assertInternalType('array', $this->block->toOptionArray());
    }
}
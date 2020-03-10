<?php

namespace Moonlay\CustomHome\Test\Unit\Block;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use Moonlay\CustomHome\Block\CategoryTree;
use PHPUnit\Framework\TestCase;

class CategoryTreeTest extends TestCase
{
    private $objectManagerHelper;
private $categoryTreeObj;
private $contextMock;
private $categoryHelperMock;
private $categoryFlatStateMock;
private $topMenuMock;
private $categoryMock;


    protected function setUp()
    {
        $this->contextMock=$this->getMockBuilder(\Magento\Framework\View\Element\Template\Context::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->categoryHelperMock=$this->getMockBuilder( \Magento\Catalog\Helper\Category::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->categoryFlatStateMock=$this->getMockBuilder( \Magento\Catalog\Model\Indexer\Category\Flat\State::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->topMenuMock=$this->getMockBuilder( \Magento\Theme\Block\Html\Topmenu::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->categoryMock=$this->getMockBuilder( \Magento\Catalog\Model\Category::class)
            ->disableOriginalConstructor()
            ->getMock();



        $this->objectManagerHelper = new ObjectManagerHelper($this);
        $this->categoryTreeObj = $this->objectManagerHelper->getObject(
            CategoryTree::class,
            [
                'context'=>$this->contextMock,
                'categoryHelper'=>$this->categoryHelperMock,
                'categoryFlatState'=>$this->categoryFlatStateMock,
                'topMenu'=> $this->topMenuMock
            ]
        );
    }



    public function testGetStoreCategories()
    {
        $this->categoryTreeObj->getStoreCategories(false,false,true);
    }

    public function testGetHtml()
    {
        $this->categoryTreeObj->getHtml();
    }

    public function testGetCategoryHelper()
    {
        $this->categoryTreeObj->getCategoryHelper();

    }

    public function testGetChildCategoriesFlatEnabled()
    {
        $this->categoryFlatStateMock->expects($this->any())->method('isFlatEnabled')->willReturn(true);
        $this->categoryMock->expects($this->any())->method('getUseFlatResource')->willReturn(true);
        $this->categoryTreeObj->getChildCategories($this->categoryMock);

    }

    public function testGetChildCategoriesFlatNotEnabled()
    {
        $result =array(false,true,false);
        $expect = array(false,true,false);
        $this->categoryFlatStateMock->expects($this->any())->method('isFlatEnabled')->willReturn(false);
        $this->categoryMock->expects($this->any())->method('getUseFlatResource')->willReturn(false);
        $this->categoryMock->expects($this->any())->method('getChildren')->willReturn($result);
        $this->categoryTreeObj->getChildCategories($this->categoryMock);
        $this->assertEquals($expect, $this->categoryTreeObj->getChildCategories($this->categoryMock));
    }
}

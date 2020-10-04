<?php

namespace Moonlay\CustomHome\Test\Unit\Block\Widget;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use Moonlay\CustomHome\Block\CategoryTree2;
use PHPUnit\Framework\TestCase;

class CategoryTree2Test extends TestCase
{
    private $contextMock;
    private $categoryHelperMock;
    private $categoryFlatStateMock;
    private $topMenuMock;
    private $categoryTree2Obj;
    private $categoryMock;

    protected function setUp()
    {

        $this->contextMock = $this->getMockBuilder(\Magento\Framework\View\Element\Template\Context::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->categoryHelperMock = $this->getMockBuilder(\Magento\Catalog\Helper\Category::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->categoryFlatStateMock = $this->getMockBuilder(\Magento\Catalog\Model\Indexer\Category\Flat\State::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->topMenuMock = $this->getMockBuilder(\Magento\Theme\Block\Html\Topmenu::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->categoryMock = $this->getMockBuilder(\Magento\Catalog\Model\Category::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->objectManagerHelper = new ObjectManagerHelper($this);
        $this->categoryTree2Obj = $this->objectManagerHelper->getObject(
            CategoryTree2::class,
            [
                'context' => $this->contextMock,
                'categoryHelper' => $this->categoryHelperMock,
                'categoryFlatState' => $this->categoryFlatStateMock,
                'topMenu' => $this->topMenuMock
            ]
        );
    }

    public function testGetCategoryHelper()
    {
        $this->categoryTree2Obj->getCategoryHelper();
    }

    public function testGetStoreCategories()
    {
        $this->categoryTree2Obj->getStoreCategories(false, false, true);
    }


    public function testGetChildCategoriesFlatEnabled()
    {
        $this->categoryFlatStateMock->expects($this->any())->method('isFlatEnabled')->willReturn(true);
        $this->categoryMock->expects($this->any())->method('getUseFlatResource')->willReturn(true);
        $this->categoryTree2Obj->getChildCategories($this->categoryMock);

    }

    public function testGetChildCategoriesFlatNotEnabled()
    {
        $result =array(false,true,false);
        $expect = array(false,true,false);
        $this->categoryFlatStateMock->expects($this->any())->method('isFlatEnabled')->willReturn(false);
        $this->categoryMock->expects($this->any())->method('getUseFlatResource')->willReturn(false);
        $this->categoryMock->expects($this->any())->method('getChildren')->willReturn($result);
        $this->categoryTree2Obj->getChildCategories($this->categoryMock);
        $this->assertEquals($expect, $this->categoryTree2Obj->getChildCategories($this->categoryMock));
    }

    public function testGetHtml()
    {
        $this->categoryTree2Obj->getHtml();
    }
}

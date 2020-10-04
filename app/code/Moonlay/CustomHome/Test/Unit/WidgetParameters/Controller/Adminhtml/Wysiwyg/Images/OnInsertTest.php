<?php

namespace Moonlay\CustomHome\Test\Unit\WidgetParameters\Controller\Adminhtml\Wysiwyg\Images;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use Moonlay\CustomHome\WidgetParameters\Controller\Adminhtml\Wysiwyg\Images\OnInsert;
use PHPUnit\Framework\TestCase;

class OnInsertTest extends TestCase
{
private $objectManagerHelper;
private $onInsertObj;
private $contextMock;
private $coreRegistryMock;
private $resultRawFactoryMock;
private $imageHelperMock;
private $requestMock;
private $resultRawMock;

    protected function setUp()
    {
        $this->resultRawMock = $this->getMockBuilder(\Magento\Framework\Controller\Result\Raw::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->requestMock=$this->getMockBuilder(\Magento\Framework\App\RequestInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->contextMock=$this->getMockBuilder(\Magento\Backend\App\Action\Context::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->contextMock->expects($this->any())
            ->method('getRequest')
            ->willReturn($this->requestMock);


        $this->coreRegistryMock=$this->getMockBuilder(\Magento\Framework\Registry::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->resultRawFactoryMock=$this->getMockBuilder(\Magento\Framework\Controller\Result\RawFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->imageHelperMock=$this->getMockBuilder(\Moonlay\CustomHome\WidgetParameters\Helper\Wysiwyg\Images::class)
            ->disableOriginalConstructor()
            ->getMock();


        $this->objectManagerHelper = new ObjectManagerHelper($this);
        $this->onInsertObj = $this->objectManagerHelper->getObject(
            OnInsert::class,
            [
                'context'=>$this->contextMock,
                'coreRegistry'=>$this->coreRegistryMock,
                'resultRawFactory'=>$this->resultRawFactoryMock,
                'imageHelper'=> $this->imageHelperMock
            ]
        );
    }

    public function testExecute()
    {

        $this->resultRawFactoryMock->expects($this->any())
            ->method('create')
            ->willReturn($this->resultRawMock);

        $this->assertNull($this->onInsertObj->execute());
    }


}

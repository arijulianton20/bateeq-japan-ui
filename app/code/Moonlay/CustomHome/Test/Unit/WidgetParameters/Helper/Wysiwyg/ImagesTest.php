<?php

namespace Moonlay\CustomHome\Test\Unit\WidgetParameters\Helper\Wysiwyg;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use Moonlay\CustomHome\WidgetParameters\Helper\Wysiwyg\Images;
use PHPUnit\Framework\TestCase;

class ImagesTest extends TestCase
{
    private $objectManagerHelper;
    private $imagesObj;
    private $contextMock;
    private $backendDataMock;
    private $filesystemMock;
    private $storeManagerMock;
    private $writeInterfaceMock;
    private $httpRequestMock;
    private $storeMock;



    protected function setUp()
    {

        $this->httpRequestMock = $this->getMockBuilder(\Magento\Framework\App\RequestInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->writeInterfaceMock = $this->getMockBuilder(\Magento\Framework\Filesystem\Directory\WriteInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->contextMock = $this->getMockBuilder(\Magento\Framework\App\Helper\Context::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->contextMock->expects($this->any())
            ->method('getRequest')
            ->willReturn($this->httpRequestMock);

        $this->backendDataMock = $this->getMockBuilder(\Magento\Backend\Helper\Data::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->filesystemMock = $this->getMockBuilder(\Magento\Framework\Filesystem::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->filesystemMock->expects($this->any())
            ->method('getDirectoryWrite')
            ->with(DirectoryList::MEDIA)
            ->willReturn($this->writeInterfaceMock);

        $this->storeMock = $this->getMockBuilder(\Magento\Store\Model\Store::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->storeManagerMock = $this->getMockBuilder(\Magento\Store\Model\StoreManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->storeManagerMock->expects($this->any())
            ->method('getStore')
            ->with(null)
            ->willReturn($this->storeMock);

        $this->objectManagerHelper = new ObjectManagerHelper($this);
        $this->imagesObj = $this->objectManagerHelper->getObject(
            Images::class,
            [
                'context' => $this->contextMock,
                'backendData' => $this->backendDataMock,
                'filesystem' => $this->filesystemMock,
                'storeManager' => $this->storeManagerMock
            ]
        );
    }

    public function testGetImagePath()
    {
        $filename = 'example.jpg';
        $this->imagesObj->getImagePath($filename);
        $this->assertEquals('/example.jpg', $this->imagesObj->getImagePath($filename));
    }
}

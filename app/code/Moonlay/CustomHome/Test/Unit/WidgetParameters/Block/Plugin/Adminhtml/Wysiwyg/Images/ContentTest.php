<?php

namespace Moonlay\CustomHome\Test\Unit\WidgetParameters\Block\Plugin\Adminhtml\Wysiwyg\Images;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use Moonlay\CustomHome\WidgetParameters\Block\Plugin\Adminhtml\Wysiwyg\Images\Content;
use PHPUnit\Framework\TestCase;

class ContentTest extends TestCase
{
    private $contentObj;
    private $requestMock;
    private $subjectMock;


protected function setUp()
{

    $this->subjectMock=$this->getMockBuilder( \Magento\Cms\Block\Adminhtml\Wysiwyg\Images\Content::class)
        ->disableOriginalConstructor()
        ->getMock();

    $this->requestMock=$this->getMockBuilder( \Magento\Framework\App\RequestInterface::class)
        ->disableOriginalConstructor()
        ->getMock();

    $this->objectManagerHelper = new ObjectManagerHelper($this);
    $this->contentObj = $this->objectManagerHelper->getObject(
        Content::class,
        [
            'request'=>$this->requestMock
        ]
    );
}

    public function testAfterGetOnInsertUrl()
    {
        $result =$this->contentObj->afterGetOnInsertUrl($this->subjectMock,'http:\\example.com');
        $this->assertEquals('http:\example.com',$result);
    }


}

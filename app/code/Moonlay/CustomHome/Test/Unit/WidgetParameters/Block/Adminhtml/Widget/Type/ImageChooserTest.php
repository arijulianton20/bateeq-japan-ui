<?php

namespace Moonlay\CustomHome\Test\Unit\WidgetParameters\Block\Adminhtml\Widget\Type;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use Moonlay\CustomHome\WidgetParameters\Block\Adminhtml\Widget\Type\ImageChooser;
use PHPUnit\Framework\TestCase;

class ImageChooserTest extends TestCase
{
    private $imageChooserObj;
    private $objectManagerHelper;
    private $contextMock;
    private $elementFactoryMock;

    private $elementMock;
    private $urlBuilderMock;
    private $layoutMock;
    private $blockInterfaceMock;
    private $inputMock;



    protected function setUp()
    {
        $this->blockInterfaceMock = $this->getMockBuilder(\Magento\Framework\View\Element\BlockInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['setType', 'setClass', 'setLabel', 'setOnClick','setDisabled'])
            ->getMockForAbstractClass();

        $this->layoutMock = $this->getMockBuilder(\Magento\Framework\View\LayoutInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->inputMock = $this->getMockBuilder(\Magento\Framework\Data\Form\Element\Text::class)
            ->disableOriginalConstructor()
            ->getMock();


        $this->urlBuilderMock = $this->getMockBuilder(\Magento\Framework\UrlInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->contextMock = $this->getMockBuilder(\Magento\Backend\Block\Template\Context::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->contextMock->expects($this->any())
            ->method('getUrlBuilder')
            ->willReturn($this->urlBuilderMock);

        $this->contextMock->expects($this->any())
            ->method('getLayout')
            ->willReturn($this->layoutMock);


        $this->elementFactoryMock = $this->getMockBuilder(\Magento\Framework\Data\Form\Element\Factory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->elementMock = $this->getMockBuilder(\Magento\Framework\Data\Form\Element\AbstractElement::class)
            ->disableOriginalConstructor()
            ->setMethods(['getId','getRequired'])
            ->getMockForAbstractClass();


        $this->objectManagerHelper = new ObjectManagerHelper($this);
        $this->imageChooserObj = $this->objectManagerHelper->getObject(
            ImageChooser::class,
            [
                'context' => $this->contextMock,
                'elementFactory' => $this->elementFactoryMock,
            ]
        );
    }

    public function testPrepareElementHtml()
    {
        $this->elementMock->expects($this->any())
            ->method('getId')
            ->willReturnOnConsecutiveCalls('1','1');

        $param = ['target_element_id' => '1', 'on_insert_url' => urlencode('any_example_url')];
        $this->urlBuilderMock->expects($this->any())
            ->method('getUrl')
            ->withConsecutive(['moonlay_widgets/wysiwyg_images/onInsert'],
                [
                    'cms/wysiwyg_images/index', $param
                ])
            ->willReturnOnConsecutiveCalls('any_example_url', 'any_example_url2');


        $this->layoutMock->expects($this->any())
            ->method('createBlock')
            ->with('Magento\Backend\Block\Widget\Button')
            ->willReturn($this->blockInterfaceMock);

        $this->blockInterfaceMock->expects($this->any())
            ->method('setType')
            ->with('button')
            ->willReturnSelf();

        $this->blockInterfaceMock->expects($this->any())
            ->method('setClass')
            ->with('btn-chooser')
            ->willReturnSelf();

        $this->blockInterfaceMock->expects($this->any())
            ->method('setLabel')
            ->with(null)
            ->willReturnSelf();

        $this->blockInterfaceMock->expects($this->any())
            ->method('setLabel')
            ->with(null)
            ->willReturnSelf();
        $paramOnclick = 'MediabrowserUtility.openDialog(\'any_example_url2\', 0, 0, "Insert File...", {})';
        $this->blockInterfaceMock->expects($this->any())
            ->method('setOnClick')
            ->with($paramOnclick)
            ->willReturnSelf();



        $data=['data' => []];
        $this->elementFactoryMock->expects($this->any())
            ->method('create')
            ->with("text",$data)
            ->willReturn($this->inputMock);

        $this->elementMock->expects($this->any())
            ->method('getRequired')
            ->willReturnSelf();

        $this->blockInterfaceMock->expects($this->any())
            ->method('setDisabled')
            ->with(null)
            ->willReturnSelf();

        $result = $this->imageChooserObj->prepareElementHtml($this->elementMock);
        $this->assertInstanceOf(\Magento\Framework\Data\Form\Element\AbstractElement::class, $result);
    }


}

<?php
/**
 * Customer attribute controller
 */
namespace Moonlay\CustomHome\Test\Unit\Controller;

use Moonlay\CustomHome\Controller\Index\Index;

class IndexTest extends \PHPUnit\Framework\TestCase
{

    private $context;
    private $pageFactory;
    protected function setUp()
    {
        $this->context = $this->getMockBuilder(\Magento\Framework\App\Action\Context::class)
        ->disableOriginalConstructor()
        ->getMock();

        $this->pageFactory = $this->getMockBuilder( \Magento\Framework\View\Result\PageFactory::class)
        ->disableOriginalConstructor()
        ->setMethods(['create'])
        ->getMock();
        $this->block = new \Moonlay\CustomHome\Controller\Index\Index(
            $this->context,
            $this->pageFactory
        );
     
    }

    public function testExecute(){
        $this->pageFactory->expects($this->once())
        ->method('create')
        ->willReturnSelf();

        $this->block->execute();
    }

}
<?php

namespace Moonlay\CustomerAttribute\Test\Unit\Block\Adminhtml\Attribute\Edit;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;

class FormTest extends \PHPUnit\Framework\TestCase
{
    private $blockFormMock;
    private $formFactoryMock;
    private $resultForm;

    protected function setUp()
    {
        $this->resultForm = $this->createMock(\Magento\Framework\Data\Form::class);
        $this->formFactoryMock = $this->createMock(\Magento\Framework\Data\FormFactory::class);
        $this->objectManagerHelper = new ObjectManagerHelper($this);
        $this->blockFormMock = $this->objectManagerHelper->getObject(
            \Moonlay\CustomerAttribute\Block\Adminhtml\Attribute\Edit\Form::class,
            [
                'formFactory' => $this->formFactoryMock,
            ]
        );
    }

    public function test_prepareForm()
    {
        $data = ['data' => ['id' => 'edit_form', 'action' => null, 'method' => 'post']];
        $this->formFactoryMock->expects($this->any())->method('create')->with($data)->willReturn($this->resultForm);

        $refClass = new \ReflectionClass(\Moonlay\CustomerAttribute\Block\Adminhtml\Attribute\Edit\Form::class);
        $_prepareForm = $refClass->getMethod('_prepareForm');
        $_prepareForm->setAccessible(true);

        $result = $_prepareForm->invoke($this->blockFormMock, []);
        $this->assertInstanceOf(\Magento\Backend\Block\Widget\Form\Generic::class, $result);

    }
}

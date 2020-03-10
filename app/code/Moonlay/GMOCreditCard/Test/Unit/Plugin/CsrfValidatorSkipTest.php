<?php

namespace Moonlay\GMOCreditCard\Test\Unit\Plugin;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use Moonlay\GMOCreditCard\Plugin\CsrfValidatorSkip;
use PHPUnit\Framework\TestCase;

class CsrfValidatorSkipTest extends TestCase
{
    private $subjectMock;
    private $CsrfValidatorSkipObj;
    private $requestMock;
    private $actionMOck;


    protected function setUp()
    {
        $this->requestMock = $this->createMock(\Magento\Framework\App\RequestInterface::class);
        $this->subjectMock = 'any';
        $this->actionMOck = $this->createMock(\Magento\Framework\App\ActionInterface::class);

        //  $this->subjectMock = $this->createMock( \Magento\Framework\App\Request\CsrfValidator::class);
        $this->objectManagerHelper = new ObjectManagerHelper($this);
        $this->CsrfValidatorSkipObj = $this->objectManagerHelper->getObject(
            CsrfValidatorSkip::class,
            [

            ]
        );
    }

    public function testAroundValidate()
    {
        $callback = function ($order, $forceSyncMode) {
            return true;
        };
        $this->CsrfValidatorSkipObj->aroundValidate( $this->subjectMock, $callback, $this->requestMock,$this->actionMOck);
    }

    public function testAroundValidateSkipCSRFcheck()
    {
        $callback = function ($order, $forceSyncMode) {
            return true;
        };
        $this->requestMock->expects($this->any())->method('getModuleName')->willReturn('gmocreditcard');
        $this->CsrfValidatorSkipObj->aroundValidate( $this->subjectMock, $callback, $this->requestMock,$this->actionMOck);
    }
}

<?php

namespace Moonlay\GMOCreditCard\Test\Unit\Model\Config\Source;

use Moonlay\GMOCreditCard\Model\Config\Source\RestrictedCountry;
use PHPUnit\Framework\TestCase;

class RestrictedCountryTest extends TestCase
{

    private $countryCollection;
    private $model;

    protected function setUp()
    {
        $this->countryCollection = $this->getMockBuilder(\Magento\Directory\Model\ResourceModel\Country\Collection::class)
        ->disableOriginalConstructor()    
        ->getMock();
       
    }

    public function testRestrictedCountry(){
        $this->countryCollection->expects($this->once())
            ->method('addCountryIdFilter')
            ->with(
                array('JP')
            )->willReturnSelf();

            $this->model = new \Moonlay\GMOCreditCard\Model\Config\Source\RestrictedCountry($this->countryCollection);
    }


}

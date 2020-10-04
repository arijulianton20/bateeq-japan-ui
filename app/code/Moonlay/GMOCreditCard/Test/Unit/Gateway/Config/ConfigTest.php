<?php

namespace Moonlay\GMOCreditCard\Test\Unit\Gateway\Config;

use Moonlay\GMOCreditCard\Gateway\Config\Config;
use PHPUnit\Framework\TestCase;
use Magento\Store\Model\ScopeInterface;

class ConfigTest extends TestCase
{
    const KEY_ACTIVE = 'active';
    const KEY_TITLE = 'title';
    const KEY_DESCRIPTION = 'description';
    const KEY_GATEWAY_LOGO = 'gateway_logo';
    const KEY_SHOP_ID = 'tshop_id';
    const KEY_SHOP_PASSWORD = 'shop_password';
    const KEY_SITE_ID = 'tsite_id';
    const KEY_SITE_PASSWORD = 'site_password';
    // const KEY_MERCHANT_NUMBER = 'merchant_number';
    // const KEY_API_KEY = 'api_key';
    const KEY_GATEWAY_URL = 'gateway_url';
    const KEY_DEBUG = 'debug';
    const KEY_SPECIFIC_COUNTRY = 'specificcountry';
    const KEY_GMO_MULTIPAYMENT_APPROVED_ORDER_STATUS = 'gmo_creditcard_approved_order_status';
    const KEY_EMAIL_CUSTOMER = 'email_customer';
    const KEY_AUTOMATIC_INVOICE = 'automatic_invoice';

    protected function setUp()
    {
        $this->scopeConfigMock = $this->getMockBuilder(\Magento\Framework\App\Config\ScopeConfigInterface::class)
        ->getMockForAbstractClass();
        $methodCode = 'code';
        $pathPattern = 'pattern/%s/%s';
      
    }

    public function testGetTitle()
    {
        $field = 'title';
        $storeId = 1;
        $methodCode = 'code';
        $pathPattern = 'pattern/%s/%s';
        $expected = 'title';

        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(
                sprintf($pathPattern, $methodCode, $field),
                ScopeInterface::SCOPE_STORE
            )->willReturn($expected);
        $this->model = new \Moonlay\GMOCreditCard\Gateway\Config\Config($this->scopeConfigMock, $methodCode, $pathPattern);
       $this->assertEquals($expected,$this->model->getTitle());
    }



    public function testGetLogo()
    {

        $field = 'gateway_logo';
        $storeId = 1;
        $methodCode = 'code';
        $pathPattern = 'pattern/%s/%s';
        $expected = 'gateway_logo';

        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(
                sprintf($pathPattern, $methodCode, $field),
                ScopeInterface::SCOPE_STORE
            )->willReturn($expected);
        $this->model = new \Moonlay\GMOCreditCard\Gateway\Config\Config($this->scopeConfigMock, $methodCode, $pathPattern);
       $this->assertEquals($expected,$this->model->getLogo());
       
    }

     public function testGetDescription()
    {
        $field = self::KEY_DESCRIPTION;
        $storeId = 1;
        $methodCode = 'code';
        $pathPattern = 'pattern/%s/%s';
        $expected = 'description';
        
        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(
                sprintf($pathPattern, $methodCode, $field),
                ScopeInterface::SCOPE_STORE
            )->willReturn($expected);
        $this->model = new \Moonlay\GMOCreditCard\Gateway\Config\Config($this->scopeConfigMock, $methodCode, $pathPattern);
       $this->assertEquals($expected,$this->model->getDescription());
    }

 public function testGetShopID()
    {
       
        $field =  self::KEY_SHOP_ID;
        $storeId = 1;
        $methodCode = 'code';
        $pathPattern = 'pattern/%s/%s';
        $expected = 'tshop_id';
        
        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(
                sprintf($pathPattern, $methodCode, $field),
                ScopeInterface::SCOPE_STORE
            )->willReturn($expected);
        $this->model = new \Moonlay\GMOCreditCard\Gateway\Config\Config($this->scopeConfigMock, $methodCode, $pathPattern);
       $this->assertEquals($expected,$this->model->getShopID());
    }

    public function testGetShopPassword()
    {
        $field =  self::KEY_SHOP_PASSWORD;
        $storeId = 1;
        $methodCode = 'code';
        $pathPattern = 'pattern/%s/%s';
        $expected = 'shop_password';
        
        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(
                sprintf($pathPattern, $methodCode, $field),
                ScopeInterface::SCOPE_STORE
            )->willReturn($expected);
        $this->model = new \Moonlay\GMOCreditCard\Gateway\Config\Config($this->scopeConfigMock, $methodCode, $pathPattern);
       $this->assertEquals($expected,$this->model->getShopPassword());
    }

     public function testGetSiteID()
    {
        $field =  self::KEY_SITE_ID;
        $storeId = 1;
        $methodCode = 'code';
        $pathPattern = 'pattern/%s/%s';
        $expected = 'tsite_id';
        
        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(
                sprintf($pathPattern, $methodCode, $field),
                ScopeInterface::SCOPE_STORE
            )->willReturn($expected);
        $this->model = new \Moonlay\GMOCreditCard\Gateway\Config\Config($this->scopeConfigMock, $methodCode, $pathPattern);
       $this->assertEquals($expected,$this->model->getSiteID());
    }

    public function testGetSitePassword()
    {
        $field =  self::KEY_SITE_PASSWORD;
        $storeId = 1;
        $methodCode = 'code';
        $pathPattern = 'pattern/%s/%s';
        $expected = 'site_password';
        
        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(
                sprintf($pathPattern, $methodCode, $field),
                ScopeInterface::SCOPE_STORE
            )->willReturn($expected);
        $this->model = new \Moonlay\GMOCreditCard\Gateway\Config\Config($this->scopeConfigMock, $methodCode, $pathPattern);
       $this->assertEquals($expected,$this->model->getSitePassword());
    }

     public function testGetGatewayUrl()
    {
        $field =  self::KEY_GATEWAY_URL;
        $storeId = 1;
        $methodCode = 'code';
        $pathPattern = 'pattern/%s/%s';
        $expected = 'gateway_url';
        
        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(
                sprintf($pathPattern, $methodCode, $field),
                ScopeInterface::SCOPE_STORE
            )->willReturn($expected);
        $this->model = new \Moonlay\GMOCreditCard\Gateway\Config\Config($this->scopeConfigMock, $methodCode, $pathPattern);
       $this->assertEquals($expected,$this->model->getGatewayUrl());
    }

      public function testGetRefundUrl()
    {
        $field =  self::KEY_GATEWAY_URL;
        $storeId = 1;
        $methodCode = 'code';
        $pathPattern = 'pattern/%s/%s';
        $expected = 'https://portals.oxipay.jp/api/ExternalRefund/processrefund';

        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(
                sprintf($pathPattern, $methodCode, $field),
                ScopeInterface::SCOPE_STORE
            )->willReturn('http://gateway_example_url.com');
        $this->model = new \Moonlay\GMOCreditCard\Gateway\Config\Config($this->scopeConfigMock, $methodCode, $pathPattern);
       $this->assertEquals($expected,$this->model->getRefundUrl());
    }

    public function testGetRefundUrlFalse()
    {
        $field =  self::KEY_GATEWAY_URL;
        $storeId = 1;
        $methodCode = 'code';
        $pathPattern = 'pattern/%s/%s';
        $expected = 'https://portalssandbox.oxipay.jp/api/ExternalRefund/processrefund';

        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(
                sprintf($pathPattern, $methodCode, $field),
                ScopeInterface::SCOPE_STORE
            )->willReturn('sandbox_url');
        $this->model = new \Moonlay\GMOCreditCard\Gateway\Config\Config($this->scopeConfigMock, $methodCode, $pathPattern);
        $this->assertEquals($expected,$this->model->getRefundUrl());
    }

    

      public function testGetApprovedOrderStatus()
    {
        $field =  self::KEY_GMO_MULTIPAYMENT_APPROVED_ORDER_STATUS;
        $storeId = 1;
        $methodCode = 'code';
        $pathPattern = 'pattern/%s/%s';
        $expected = 'gmo_creditcard_approved_order_status';
        
        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(
                sprintf($pathPattern, $methodCode, $field),
                ScopeInterface::SCOPE_STORE
            )->willReturn($expected);
        $this->model = new \Moonlay\GMOCreditCard\Gateway\Config\Config($this->scopeConfigMock, $methodCode, $pathPattern);
       $this->assertEquals($expected,$this->model->getApprovedOrderStatus());
    }

        public function testIsEmailCustomer()
    {
        $field =  self::KEY_EMAIL_CUSTOMER;
        $storeId = 1;
        $methodCode = 'code';
        $pathPattern = 'pattern/%s/%s';
        $expected = true;
        
        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(
                sprintf($pathPattern, $methodCode, $field),
                ScopeInterface::SCOPE_STORE
            )->willReturn($expected);
        $this->model = new \Moonlay\GMOCreditCard\Gateway\Config\Config($this->scopeConfigMock, $methodCode, $pathPattern);
       $this->assertEquals($expected,$this->model->isEmailCustomer());
    }

      public function testIsAutomaticInvoice()
    {
        $field =  self::KEY_AUTOMATIC_INVOICE;
        $storeId = 1;
        $methodCode = 'code';
        $pathPattern = 'pattern/%s/%s';
        $expected = true;
        
        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(
                sprintf($pathPattern, $methodCode, $field),
                ScopeInterface::SCOPE_STORE
            )->willReturn($expected);
        $this->model = new \Moonlay\GMOCreditCard\Gateway\Config\Config($this->scopeConfigMock, $methodCode, $pathPattern);
       $this->assertEquals($expected,$this->model->isAutomaticInvoice());
    }

     public function testIsActive()
    {
        $field =  self::KEY_ACTIVE;
        $storeId = 1;
        $methodCode = 'code';
        $pathPattern = 'pattern/%s/%s';
        $expected = true;
        
        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(
                sprintf($pathPattern, $methodCode, $field),
                ScopeInterface::SCOPE_STORE
            )->willReturn($expected);
        $this->model = new \Moonlay\GMOCreditCard\Gateway\Config\Config($this->scopeConfigMock, $methodCode, $pathPattern);
       $this->assertEquals($expected,$this->model->isActive());
    }

    public function testGetSpecificCountry()
    {
        $field =  self::KEY_SPECIFIC_COUNTRY;
        $storeId = 1;
        $methodCode = 'code';
        $pathPattern = 'pattern/%s/%s';
        $expected = 'specificcountry';
        
        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(
                sprintf($pathPattern, $methodCode, $field),
                ScopeInterface::SCOPE_STORE
            )->willReturn($expected);
        $this->model = new \Moonlay\GMOCreditCard\Gateway\Config\Config($this->scopeConfigMock, $methodCode, $pathPattern);
       $this->assertEquals($expected,$this->model->getSpecificCountry());
    }
 
}

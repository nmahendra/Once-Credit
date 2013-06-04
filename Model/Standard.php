<?php

class Pushys_Once_Model_Standard extends Mage_Payment_Model_Method_Abstract {

    protected $_code = 'once';
    protected $_isInitializeNeeded = true;
    protected $_canUseInternal = true;
    protected $_canUseForMultishipping = true;


    public function getOrderPlaceRedirectUrl() {
        $url_api = $this->getConfigData('url_api');
        $company = $this->getConfigData('company');
        $merchantId = $this->getConfigData('merchant_id');
        $opratorId = $this->getConfigData('operator_id');
        $password = $this->getConfigData('password');
        $product = $this->getConfigData('product');
        $transactionId = Mage::getSingleton('checkout/session')->getData('BeginIPLTransactionID');
        
        return $url_api . '?company=' . $company . '&merchant=' . $merchantId . '&operator=' . $opratorId . '&password=' . $password . '&product=' . $product . '&transaction=' . $transactionId;
        
    }
}
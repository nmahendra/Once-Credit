<?php

class Pushys_Once_Model_Oncecreditsoap extends SoapClient {
    protected $_wsdl = 'https://www.training.mybuyonline.com.au/IPL_service/ipltransaction.asmx?wsdl';
    
    public function __construct($options = array()) {
        parent::__construct($this->_wsdl, $options);
        return true;
    }
}
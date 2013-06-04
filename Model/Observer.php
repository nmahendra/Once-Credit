<?php

class Pushys_Once_Model_Observer{
    
    public function saveOnceCredit(Varien_Event_Observer $observer){

        $requestObj = Mage::getModel('once/oncecreditsoap');
        $storeId = Mage::app()->getStore()->getId();
    	$customerData = Mage::getSingleton('checkout/session')->getQuote()->getBillingAddress();
        $order = $observer->getEvent()->getOrder();
    	$amount = 0;
        
	$request = new stdClass();
        $request->MerchantId = Mage::getStoreConfig('payment/once/merchant_id');
        $request->OperatorId = Mage::getStoreConfig('payment/once/operator_id');
        $request->Password = Mage::getStoreConfig('payment/once/password');
        $request->Offer = Mage::getStoreConfig('payment/once/offer');
        $request->CreditProduct = Mage::getStoreConfig('payment/once/product');
        $request->OrderNumber = $order->getId();
        $request->Amount = $order->getGrandTotal();
        $request->ExistingCustomer = false;
        $request->Title = Mage::getStoreConfig('payment/once/title');;
        $request->FirstName = $customerData->getData('firstname');
        $request->Surname = $customerData->getData('lastname');

	$billingAddress = new stdClass();
        $billingAddress->AddressType = 'Delivery';
        $billingAddress->StreetNumber = '';
        $billingAddress->StreetName = '';
        $billingAddress->StreetType = '';
        $billingAddress->Suburb = '';
        $billingAddress->City = '';
        $billingAddress->Postcode = '';
        $request->BillingAddress = $billingAddress;

        $deliveryAddress = new stdClass();
        $deliveryAddress->AddressType = 'Delivery';
        $deliveryAddress->StreetNumber = '';
        $deliveryAddress->StreetName = '';
        $deliveryAddress->StreetType = '';
        $deliveryAddress->Suburb = '';
        $deliveryAddress->City = '';
        $deliveryAddress->Postcode = '';
        $request->DeliveryAddress = $deliveryAddress;

	$request->ReturnApprovedUrl = Mage::getUrl('once/payment/response', array('_secure' => true));
        $request->ReturnDeclineUrl = Mage::getUrl('once/standard/failure', array('_secure' => true));
        $request->ReturnWithdrawUrl = Mage::getUrl('once/standard/failure', array('_secure' => true));

        $stdRequest = new stdClass();
	$stdRequest->TransactionInformation = $request;
        $stdRequest->SecretKey = Mage::getStoreConfig('payment/once/secret_key');
        
	try {
            $result = $requestObj->BeginIPLTransaction($stdRequest);
            $transactionId = $result->BeginIPLTransactionResult;
            Mage::getSingleton('checkout/session')->setData('BeginIPLTransactionID', $transactionId);
        } catch (Exception $e) {
            return Mage::helper('checkout')->__('ONCE Credit server error.');
        }
        return true;
    }
}
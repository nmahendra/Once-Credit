<?php

class Pushys_Once_PaymentController extends Mage_Core_Controller_Front_Action {
    
    public function responseAction(){
        
        $session = $this->getOnepage()->getCheckout();
        if (!$session->getLastSuccessQuoteId()) {
            $this->_redirect('checkout/cart');
            return;
        }      
        
        $lastQuoteId = $session->getLastQuoteId();
        $lastOrderId = $session->getLastOrderId();
        $lastRecurringProfiles = $session->getLastRecurringProfileIds();
        if (!$lastQuoteId || (!$lastOrderId && empty($lastRecurringProfiles))) {
            $this->_redirect('checkout/cart');
            return;
        }    
        
        print_r($lastRecurringProfiles);
        
        /*
        if($this->getRequest()->isPost()){
            $validated = true;
            $orderId = "3600007920 ";
            
            if($validated) {
                $order = Mage::getModel('sales/order');
                $order->loadByIncrementId($orderId);
                $order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true, 'Gateway has authorized the payment.');
                $order->sendNewOrderEmail();
                $order->setEmailSent(true);
                $order->save();
                Mage::getSingleton('checkout/session')->unsQuoteId();
                Mage_Core_Controller_Varien_Action::_redirect('checkout/onepage/success', array('_secure'=>true));
            }else{
                $this->cancelAction();   
                Mage_Core_Controller_Varien_Action::_redirect('checkout/onepage/failure', array('_secure'=>true));
            }
        }else{
            Mage_Core_Controller_Varien_Action::_redirect('');
        }
         * 
         */
    }
    
    public function failureAction() {
        if (Mage::getSingleton('checkout/session')->getLastRealOrderId()) {
            $order = Mage::getModel('sales/order')->loadByIncrementId(Mage::getSingleton('checkout/session')->getLastRealOrderId());
            if ($order->getId()) {
                $order->cancel()->setState(Mage_Sales_Model_Order::STATE_CANCELED, true, 'Gateway has declined the payment.')->save();
            }
        }
    }

}
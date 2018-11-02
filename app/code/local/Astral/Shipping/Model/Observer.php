<?php
class Astral_Shipping_Model_Observer{
    public function hideShippingMethods( Varien_Event_Observer $observer ){
        Mage::log(__METHOD__);
            if (Mage::getDesign()->getArea() ===   Mage_Core_Model_App_Area::AREA_FRONTEND){
            $quote              = $observer->getEvent()->getQuote();
            $address            = $quote->getShippingAddress();
            $store              = Mage::app()->getStore($quote->getStoreId());
            $carriers           = Mage::getStoreConfig('carriers', $store);
            $hiddenMethodCode   = 'freeshipping';
            $cc = $quote->getCouponCode();
            $sfs = false;
                if(!isset($cc)||empty($cc)) {
                $this->hideFreeShipping($carriers);
                }elseif(isset($cc)&&!empty($cc)){
                $oCoupon = Mage::getModel('salesrule/coupon')->load($quote->getCouponCode(), 'code');
                $oRule = Mage::getModel('salesrule/rule')->load($oCoupon->getRuleId());
                Mage::log(__METHOD__." CouponCode ".$quote->getCouponCode());
                Mage::log(__METHOD__." simple_free_shipping ".$oRule->getData('simple_free_shipping'));
                $sfs = $oRule->getData('simple_free_shipping');
                    if ($sfs == 0) {
                        $this->hideFreeShipping($carriers);
                    }
                }
            }
    }

    public function hideFreeShipping($carriers){
        Mage::log(__METHOD__);
        $hiddenMethodCode = 'freeshipping';
            foreach ($carriers as $carrierCode => $carrierConfig){
                if( $carrierCode ==  $hiddenMethodCode ){
                $store->setConfig("carriers/{$carrierCode}/active", '0');
                }
            }
    }

}
<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Xcoupon
 */  
class Amasty_Xcoupon_Model_Salesrule_Mysql4_Rule_Collection
    extends Amasty_Xcoupon_Model_Salesrule_Mysql4_Rule_Collection_Pure
{
    public function setValidationFilter($websiteId, $customerGroupId, $couponCode='', $now=null)
    {
        if (version_compare(Mage::getVersion(), '1.4.1.0') < 0) { 
            return parent::setValidationFilter($websiteId, $customerGroupId, $couponCode, $now);
        }
        
        if ($this->getFlag('validation_filter')) {
            return $this;
        }        
        
        if ($couponCode) {
            $isMultipleCoupons = $this->_isAmastyCouponsEnable();
            if ($isMultipleCoupons && !is_array($couponCode)) {
                $couponCode = explode(',', $couponCode);
            }
        }
        
        $this->getSelect()->reset();
        $this->getSelect()->from(array('main_table' => $this->getTable('salesrule/rule')));
        $this->addWebsiteGroupDateFilter($websiteId, $customerGroupId, $now);
        $couponCode = is_array($couponCode) ? array_map('trim', $couponCode) : $couponCode;
        if ($couponCode) {
            $this->getSelect()->joinLeft(array('c' => $this->getTable('salesrule/coupon')),
                'main_table.rule_id = c.rule_id ', 
                array('code')
            ); 
            // Mage_SalesRule_Model_Rule::COUPON_TYPE_NO_COUPON is not defined
            $this->getSelect()->where(
                $this->getSelect()->getAdapter()->quoteInto(' main_table.coupon_type = ?', 1)
                .
                $this->getSelect()->getAdapter()->quoteInto(' OR c.code IN(?)', $couponCode)
            );
            
            $this->getSelect()->group('main_table.rule_id');
        } else {
            $this->getSelect()->where('main_table.coupon_type = ?', 1);
        }
        
        $this->getSelect()->order('sort_order'); 
        
        $this->setFlag('validation_filter', true);
        
        return $this;
    }
    
    public function addWebsiteGroupDateFilter($websiteId, $customerGroupId, $now = null)
    {
        if (method_exists('Mage', 'getEdition')) { // available from CE 1.7
            return parent::addWebsiteGroupDateFilter($websiteId, $customerGroupId, $now); 
        }
         
        $this->getSelect()->where('is_active=1');
        $this->getSelect()->where('find_in_set(?, website_ids)', (int)$websiteId);
        $this->getSelect()->where('find_in_set(?, customer_group_ids)', (int)$customerGroupId); 
        
        if (is_null($now)) {
            $now = Mage::getModel('core/date')->date('Y-m-d');
        }        
        $this->getSelect()->where('from_date is null or from_date<=?', $now);
        $this->getSelect()->where('to_date is null or to_date>=?', $now);
               
        return $this;
    }

    /**
     * check enable Amasty Coupons
     * @return bool
     */
    protected function _isAmastyCouponsEnable()
    {
        if ((string)Mage::getConfig()->getNode('modules/Amasty_Coupons/active')
            && Mage::helper('ambase')->isModuleActive('Amasty_Coupons')
        ) {
            return true;
        }

        return false;
    }
}
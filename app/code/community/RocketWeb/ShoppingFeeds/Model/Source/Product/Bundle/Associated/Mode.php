<?php

class RocketWeb_ShoppingFeeds_Model_Source_Product_Bundle_Associated_Mode extends Varien_Object
{

    const ONLY_BUNDLE = 0;
    const ONLY_ASSOCIATED = 1;
    const BOTH_BUNDLE_ASSOCIATED = 2;

    public function toOptionArray()
    {
        $vals = array(
            self::ONLY_BUNDLE => Mage::helper('rocketshoppingfeeds')->__('Only parent / No sub-item'),
            self::ONLY_ASSOCIATED => Mage::helper('rocketshoppingfeeds')->__('No parent / Only sub-item'),
            self::BOTH_BUNDLE_ASSOCIATED => Mage::helper('rocketshoppingfeeds')->__('Both types - parent and all sub-items'),
        );

        $options = array();
        foreach ($vals as $k => $v) {
            $options[] = array('value' => $k, 'label' => $v);
        }

        return $options;
    }

    public function toArray(array $arrAttributes = array())
    {
        $vals = array(
            self::ONLY_BUNDLE => Mage::helper('rocketshoppingfeeds')->__('Only parent / No sub-item'),
            self::ONLY_ASSOCIATED => Mage::helper('rocketshoppingfeeds')->__('No parent / Only sub-item'),
            self::BOTH_BUNDLE_ASSOCIATED => Mage::helper('rocketshoppingfeeds')->__('Both types - parent and all sub-items'),
        );

        $options = array();
        foreach ($vals as $k => $v) {
            $options[$k] = $v;
        }

        return $options;
    }
}
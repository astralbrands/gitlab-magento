<?php

/**
 * RocketWeb
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category  RocketWeb
 * @package   RocketWeb_ShoppingFeeds
 * @copyright Copyright (c) 2016 RocketWeb (http://rocketweb.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author    RocketWeb
 */
class RocketWeb_ShoppingFeeds_Model_Source_Product_Associated_Mode extends Varien_Object
{

    const ONLY_PARENT = 0;
    const ONLY_ASSOCIATED = 1;
    const BOTH_PARENT_ASSOCIATED = 2;

    public function toOptionArray()
    {
        $vals = array(
            self::ONLY_ASSOCIATED => Mage::helper('rocketshoppingfeeds')->__('No parent product / Only associated products'),
            self::ONLY_PARENT => Mage::helper('rocketshoppingfeeds')->__('Only parent / No associated products'),
            self::BOTH_PARENT_ASSOCIATED => Mage::helper('rocketshoppingfeeds')->__('Both types - parent product and associated products'),
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
            self::ONLY_ASSOCIATED => Mage::helper('rocketshoppingfeeds')->__('No parent product / Only associated products'),
            self::ONLY_PARENT => Mage::helper('rocketshoppingfeeds')->__('Only parent / No associated products'),
            self::BOTH_PARENT_ASSOCIATED => Mage::helper('rocketshoppingfeeds')->__('Both types - parent product and associated products'),
        );

        $options = array();
        foreach ($vals as $k => $v) {
            $options[$k] = $v;
        }

        return $options;
    }

}
<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright  Copyright (c) 2006-2016 X.commerce, Inc. and affiliates (http://www.magento.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Used in creating options for Yes|No config value selection
 *
 */
class RocketWeb_ShoppingFeeds_Model_Source_Yesnoauto extends Varien_Object
{

    const AUTO = 2;
    const YES = 1;
    const NO = 0;

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => self::AUTO, 'label'=>Mage::helper('adminhtml')->__('Auto')),
            array('value' => self::YES, 'label'=>Mage::helper('adminhtml')->__('Yes')),
            array('value' => self::NO, 'label'=>Mage::helper('adminhtml')->__('No')),
        );
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray(array $arrAttributes = array())
    {
        return array(
            self::NO => Mage::helper('adminhtml')->__('No'),
            self::YES => Mage::helper('adminhtml')->__('Yes'),
            self::AUTO => Mage::helper('adminhtml')->__('Auto'),
        );
    }

}
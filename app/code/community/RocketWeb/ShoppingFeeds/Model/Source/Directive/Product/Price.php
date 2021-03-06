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
class RocketWeb_ShoppingFeeds_Model_Source_Directive_Product_Price extends Varien_Object
{

    /**
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 1, 'label' => Mage::helper('rocketshoppingfeeds')->__('Yes')),
            array('value' => 0, 'label' => Mage::helper('rocketshoppingfeeds')->__('No')),
        );
    }

    /**
     * @return string
     */
    public function toHtml()
    {
        $html = '<div style="float:left;">'. Mage::helper('rocketshoppingfeeds')->__('Add Tax:'). '</div>'
            . '<div style="float:right;"><select name="config[#{field_name}][#{_id}][param]" class="select" style="width:180px;">';

        $options = $this->toOptionArray();
        foreach ($options as $option) {
            $html .= '<option value="' . $option['value'] . '">' . $option['label'] . '</option>';
        }
        $html .= '</select></div>';

        $html .= '<p class="note" style="clear:both;"><span>' . Mage::helper('rocketshoppingfeeds')->__('US feeds should not include tax.') . '</span></p>';
        return $html;
    }
}
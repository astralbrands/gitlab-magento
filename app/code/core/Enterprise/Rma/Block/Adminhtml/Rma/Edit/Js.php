<?php
/**
 * Magento Enterprise Edition
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magento Enterprise Edition End User License Agreement
 * that is bundled with this package in the file LICENSE_EE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magento.com/license/enterprise-edition
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
 * @category    Enterprise
 * @package     Enterprise_Rma
 * @copyright Copyright (c) 2006-2018 Magento, Inc. (http://www.magento.com)
 * @license http://www.magento.com/license/enterprise-edition
 */

class Enterprise_Rma_Block_Adminhtml_Rma_Edit_Js extends Mage_Adminhtml_Block_Template
{

    public function _construct()
    {
        parent::_construct();
        $this->setRmaId(Mage::registry('current_rma')->getId());
    }

    /**
     * Get url for Details AJAX Action
     *
     * @return string
     */
    public function getLoadAttributesUrl()
    {
        return $this->getUrl('*/*/loadAttributes', array(
            'id' => Mage::registry('current_rma')->getId()
        ));
    }

    /**
     * Get url for Split Line AJAX Action
     *
     * @return string
     */
    public function getLoadSplitLineUrl()
    {
        return $this->getUrl('*/*/loadSplitLine', array(
            'id' => Mage::registry('current_rma')->getId()
        ));
    }

    /**
     * Get url for Shipping Methods Action
     *
     * @return string
     */
    public function getLoadShippingMethodsUrl()
    {
        return $this->getUrl('*/*/showShippingMethods', array(
            'id' => Mage::registry('current_rma')->getId()
        ));
    }

    /**
     * Get url for Psl Action
     *
     * @return string
     */
    public function getLoadPslUrl()
    {
        return $this->getUrl('*/*/psl', array(
            'id' => Mage::registry('current_rma')->getId()
        ));
    }
}
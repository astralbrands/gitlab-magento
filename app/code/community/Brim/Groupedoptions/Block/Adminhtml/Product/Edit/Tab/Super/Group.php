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
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
/**
 * @category    Brim
 * @package     Brim_Groupedoptions
 * @copyright   Copyright (c) 2011-2012 Brim LLC. (http://www.brimllc.com)
 */
class Brim_Groupedoptions_Block_Adminhtml_Product_Edit_Tab_Super_Group
    extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Super_Group
{

	/**
	 * Overriding this method to remove the 'required option' filter. We do this because required options are filtered
	 * and we want to be able to associate simple products with required options to grouped products.
	 * (non-PHPdoc)
	 * @see app/code/core/Mage/Adminhtml/Block/Catalog/Product/Edit/Tab/Super/Group.php
     *  Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Super_Group->_prepareCollection()
	 */
	protected function _prepareCollection()
	{
		$allowProductTypes = array();
		foreach (Mage::getConfig()->getNode('global/catalog/product/type/grouped/allow_product_types')->children() as $type) {
			$allowProductTypes[] = $type->getName();
		}

		$collection = Mage::getModel('catalog/product_link')->useGroupedLinks()
            ->getProductCollection()
            ->setProduct($this->_getProduct())
            ->addAttributeToSelect('*')
            /* BEGIN Brim Grouped-Options Customizations */
            //->addFilterByRequiredOptions()
            /* END Brim Grouped-Options Customizations */
            ->addAttributeToFilter('type_id', $allowProductTypes);

		$this->setCollection($collection);
		return Mage_Adminhtml_Block_Widget_Grid::_prepareCollection();
	}
}
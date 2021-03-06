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
 * @category   RocketWeb
 * @package    RocketWeb_ShoppingFeeds
 * @copyright  Copyright (c) 2016 RocketWeb (http://rocketweb.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     RocketWeb
 */

class RocketWeb_ShoppingFeeds_Model_Mysql4_Feed_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    /**
     * Load data for preview flag
     *
     * @var bool
     */
    protected $_previewFlag;

    /**
     * Define resource model
     *
     */
    protected function _construct()
    {
        $this->_init('rocketshoppingfeeds/feed');
    }

    /**
     * Set first store flag
     *
     * @param bool $flag
     * @return Mage_Cms_Model_Resource_Page_Collection
     */
    public function setFirstStoreFlag($flag = false)
    {
        $this->_previewFlag = $flag;
        return $this;
    }

    /**
     * Set store filter
     *
     * @param int $storeId
     * @return $this
     */
    public function addStoreFilter($storeId)
    {
        return $this->addFilter('store_id', $storeId);
    }

    /**
     * @param RocketWeb_ShoppingFeeds_Model_Mysql4_Feed_Collection $collection
     */
    public function disableMicrodataOnCollection($collection) {
        $this->getConnection('core_write')->update(
            $this->getResource()->getMainTable(),
            array('use_for_microdata' => 0),
            array('id' => $collection->getAllIds())
        );
    }
}
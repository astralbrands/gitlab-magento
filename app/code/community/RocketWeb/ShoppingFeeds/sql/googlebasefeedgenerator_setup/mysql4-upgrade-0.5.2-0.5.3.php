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
 */

/**
 * @category   RocketWeb
 * @package    RocketWeb_ShoppingFeeds
 * @author     RocketWeb
 */

/** @var $installer RocketWeb_ShoppingFeeds_Model_Resource_Eav_Mysql4_Setup */
$installer = $this;
$installer->startSetup();

if (!$installer->getConnection()->tableColumnExists($this->getTable('rocketshoppingfeeds/feed_ftp'), 'path')) {
    $installer->run("ALTER TABLE {$installer->getTable('rocketshoppingfeeds/feed_ftp')} ADD COLUMN `path` varchar(255) NOT NULL COMMENT 'FTP Path' after `port`;");
}

$installer->endSetup();

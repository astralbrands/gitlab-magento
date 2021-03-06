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

$installer->run("
DROP TABLE IF EXISTS {$this->getTable('rocketshoppingfeeds/feed_ftp')};

CREATE TABLE `{$this->getTable('rocketshoppingfeeds/feed_ftp')}` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Ftp Id',
  `feed_id` int(10) unsigned NOT NULL COMMENT 'Feed Id',
  `username` varchar(255) NOT NULL COMMENT 'FTP Username',
  `password` varchar(255) NOT NULL COMMENT 'FTP Password',
  `host` varchar(255) NOT NULL COMMENT 'FTP Host',
  `port` int(10) unsigned NOT NULL COMMENT 'FTP Port',
  PRIMARY KEY (`id`),
  KEY `IDX_RW_GFEED_FEED_FTP_FEED_ID` (`feed_id`),
  CONSTRAINT `FK_RSF_FEED_FTP_FEED_ID_RSF_FEED_ID` FOREIGN KEY (`feed_id`) REFERENCES `rw_gfeed_feed` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Feed Queue table';
");

$installer->endSetup();

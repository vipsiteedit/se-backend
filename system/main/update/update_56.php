<?php
se_db_query("
CREATE TABLE IF NOT EXISTS `shop_coupons` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`code` varchar(50) NOT NULL,
`type` enum('p','a') NOT NULL DEFAULT 'p',
`discount` float(10,2) DEFAULT NULL,
`currency` char(3) NOT NULL DEFAULT 'RUR',
`expire_date` date DEFAULT NULL,
`min_sum_order` float(10,2) DEFAULT NULL,
`status` enum('Y','N') NOT NULL DEFAULT 'Y',
`count_used` int(10) unsigned NOT NULL DEFAULT '1',
`payment_id` int(10) unsigned DEFAULT NULL,
`only_registered` enum('Y','N') NOT NULL DEFAULT 'N',
`updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
`created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY (`id`),
UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
");

<?php
se_db_query("
CREATE TABLE IF NOT EXISTS `shop_delivery_param` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`id_delivery` int(10) unsigned NOT NULL,
`type_param` enum('sum','weight','volume') DEFAULT 'sum',
`price` double(16,2) unsigned NOT NULL,
`min_value` double(16,3) DEFAULT NULL,
`max_value` double(16,3) DEFAULT NULL,
`priority` int(11) DEFAULT '0',
`operation` enum('=','+','-') DEFAULT '=',
`type_price` enum('a','s','d') DEFAULT 'a',
`updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
`created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY (`id`),
KEY `id_delivery` (`id_delivery`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
");

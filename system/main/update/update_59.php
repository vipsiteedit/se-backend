<?php
se_db_query("
CREATE TABLE IF NOT EXISTS `shop_delivery_payment` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`id_delivery` int(10) unsigned NOT NULL,
`id_payment` int(10) unsigned NOT NULL,
`updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
`created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY (`id`),
UNIQUE KEY `untypegroup` (`id_payment`,`id_delivery`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8;
");

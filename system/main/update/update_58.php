<?php
se_db_query("
CREATE TABLE IF NOT EXISTS `shop_delivery_region` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`id_delivery` int(10) unsigned NOT NULL,
`id_country` int(11) DEFAULT NULL,
`id_region` int(11) DEFAULT NULL,
`id_city` int(11) DEFAULT NULL,
`updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
`created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY (`id`),
KEY `id_delivery` (`id_delivery`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
");

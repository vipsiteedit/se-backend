<?php
se_db_query("CREATE TABLE IF NOT EXISTS `shop_delivery_payment` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`id_delivery` int(10) unsigned NOT NULL,
`id_payment` int(10) unsigned NOT NULL,
`updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
`created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY (`id`),
UNIQUE KEY `del_pay_index` (`id_delivery`,`id_payment`),
KEY `id_delivery` (`id_delivery`),
KEY `id_payment` (`id_payment`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;");


se_db_query("ALTER TABLE `shop_delivery_payment`
ADD CONSTRAINT `shop_delivery_payment_ibfk_1` FOREIGN KEY (`id_delivery`) REFERENCES `shop_deliverytype` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `shop_delivery_payment_ibfk_2` FOREIGN KEY (`id_payment`) REFERENCES `shop_payment` (`id`) ON DELETE CASCADE;");

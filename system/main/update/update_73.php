<?php
se_db_query("
CREATE TABLE IF NOT EXISTS `shop_coupons_history` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`code_coupon` varchar(50) NOT NULL,
`id_coupon` int(10) unsigned NOT NULL,
`id_user` int(10) unsigned DEFAULT NULL,
`id_order` int(10) unsigned NOT NULL,
`discount` float(10,2) DEFAULT NULL,
`updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
`created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY (`id`),
KEY `id_coupon` (`id_coupon`),
KEY `id_user` (`id_user`),
KEY `id_order` (`id_order`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
");

se_db_query("
ALTER TABLE `shop_coupons_history`
ADD CONSTRAINT `shop_coupons_history_fk` FOREIGN KEY (`id_coupon`) REFERENCES `shop_coupons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `shop_coupons_history_fk1` FOREIGN KEY (`id_order`) REFERENCES `shop_order` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
");
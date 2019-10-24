<?php
se_db_query("
CREATE TABLE IF NOT EXISTS `shop_coupons_goods` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`coupon_id` int(10) unsigned NOT NULL,
`group_id` int(10) unsigned DEFAULT NULL,
`price_id` int(10) unsigned DEFAULT NULL,
`updated_at` timestamp NULL DEFAULT NULL,
`created_at` timestamp NULL DEFAULT NULL,
 PRIMARY KEY (`id`),
UNIQUE KEY `idkey` (`coupon_id`,`group_id`,`price_id`),
KEY `group_id` (`group_id`),
KEY `price_id` (`price_id`),
KEY `coupon_id` (`coupon_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
");

se_db_query("ALTER TABLE `shop_coupons_goods`
  ADD CONSTRAINT `shop_coupons_goods_ibfk_1` FOREIGN KEY (`coupon_id`) REFERENCES `shop_coupons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;");

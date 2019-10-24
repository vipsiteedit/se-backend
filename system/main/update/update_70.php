<?php
se_db_query("
CREATE TABLE IF NOT EXISTS `shop_discount_links` (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`discount_id` int(10) unsigned NOT NULL,
`id_price` int(10) unsigned DEFAULT NULL,
`id_group` int(10) unsigned DEFAULT NULL,
`id_user` int(10) unsigned DEFAULT NULL,
`priority` smallint(5) unsigned DEFAULT NULL,
`type` enum('g','p','o','m','i') NOT NULL DEFAULT 'm',
`updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
`created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY (`id`),
KEY `id_price` (`id_price`),
KEY `id_group` (`id_group`),
KEY `id_user` (`id_user`),
KEY `updated_at` (`updated_at`),
KEY `discount_id` (`discount_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
");

se_db_query("ALTER TABLE `shop_discount_links`
  ADD CONSTRAINT `shop_discount_links_ibfk_1` FOREIGN KEY (`discount_id`) REFERENCES `shop_discounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;");

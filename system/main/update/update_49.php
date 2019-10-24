<?php
se_db_query("
CREATE TABLE IF NOT EXISTS `shop_crossgroup` (
`id` int(10) unsigned NOT NULL,
`group_id` int(10) unsigned DEFAULT NULL,
`updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
`created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
UNIQUE KEY `id_groupid_uni` (`id`,`group_id`),
KEY `id` (`id`),
KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");


se_db_query("ALTER TABLE `shop_crossgroup` ADD CONSTRAINT `shop_crossgroup_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `shop_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `shop_crossgroup_ibfk_1` FOREIGN KEY (`id`) REFERENCES `shop_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;");

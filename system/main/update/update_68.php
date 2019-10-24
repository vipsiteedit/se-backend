<?php
se_db_query("
CREATE TABLE IF NOT EXISTS `shop_group_img` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`id_group` int(10) unsigned NOT NULL,
`picture` varchar(255) DEFAULT NULL,
`picture_alt` varchar(255) DEFAULT NULL,
`title` varchar(255) DEFAULT NULL,
`sort` int(11) NOT NULL DEFAULT '0',
`updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
`created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY (`id`),
KEY `id_group` (`id_group`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;");

se_db_query("ALTER TABLE `shop_group_img`
ADD CONSTRAINT `group_img_ibfk_2` FOREIGN KEY (`id_group`) REFERENCES `shop_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;");

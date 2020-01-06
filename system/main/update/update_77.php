<?php

se_db_query("CREATE TABLE IF NOT EXISTS `shop_modifications` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`id_mod_group` int(10) unsigned NOT NULL,
`id_price` int(10) unsigned NOT NULL,
`code` varchar(40) DEFAULT NULL,
`value` double(10,2) NOT NULL,
`value_opt` double(10,2) NOT NULL,
`value_opt_corp` double(10,2) NOT NULL,
`count` double(10,3) DEFAULT NULL,
`sort` int(10) NOT NULL DEFAULT '0',
`default` tinyint(1) unsigned NOT NULL DEFAULT '0',
`id_exchange` varchar(40) DEFAULT NULL,
`description` text NOT NULL,
`updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
`created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY (`id`),
KEY `id_mod_group` (`id_mod_group`),
KEY `price_id` (`id_price`),
KEY `sort` (`sort`),
KEY `id_exchange` (`id_exchange`),
ADD CONSTRAINT `FK_shop_modifications_shop_modifications_group_id` FOREIGN KEY (`id_mod_group`) REFERENCES `shop_modifications_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `shop_modifications_ibfk_1` FOREIGN KEY (`id_price`) REFERENCES `shop_price` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");


if (!se_db_is_field('shop_modifications','value_opt')){ 
    se_db_add_field('shop_modifications', 'value_opt', "double(10,2) NOT NULL AFTER `value`");
}
if (!se_db_is_field('shop_modifications','value_opt_corp')){ 
    se_db_add_field('shop_modifications', 'value_opt_corp', "double(10,2) NOT NULL AFTER `value_opt`");
}

if (!se_db_is_field('shop_modifications','`default`')){ 
    se_db_query("ALTER TABLE  `shop_modifications` ADD `default` BOOLEAN NOT NULL DEFAULT FALSE AFTER `sort`, ADD INDEX (  `default` )");
}

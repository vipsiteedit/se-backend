<?php

if (!se_db_is_field('shop_modifications','id_exchange')){ 
    se_db_add_field('shop_modifications', 'id_exchange', "varchar(40) default NULL AFTER `default`");
}

if (!se_db_is_field('shop_modifications','code')){ 
    se_db_add_field('shop_modifications', 'code', "varchar(40) default NULL AFTER `id_price`");
}


if (!se_db_is_field('shop_modifications','description')){ 
    se_db_add_field('shop_modifications', 'description', "TEXT  AFTER `id_exchange`");
}

se_db_query("CREATE TABLE IF NOT EXISTS `shop_rating` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`id_price` int(10) unsigned NOT NULL,
`id_user` int(10) unsigned NOT NULL,
`mark` smallint(1) unsigned NOT NULL,
`updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
`created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY (`id`),
UNIQUE KEY `UK_shop_rating` (`id_price`,`id_user`),
KEY `FK_shop_rating_se_user_id` (`id_user`),
CONSTRAINT `FK_shop_rating_se_user_id` FOREIGN KEY (`id_user`)
REFERENCES `se_user` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
CONSTRAINT `FK_shop_rating_shop_price_id` FOREIGN KEY (`id_price`) 
REFERENCES `shop_price` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;");
<?php
se_db_query("
CREATE TABLE IF NOT EXISTS `shop_brand` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`lang` char(3) NOT NULL DEFAULT 'rus',
`name` varchar(255) NOT NULL,
`code` varchar(255) NOT NULL,
`image` varchar(255) DEFAULT NULL,
`text` text,
`title` varchar(255) DEFAULT NULL,
`keywords` varchar(255) DEFAULT NULL,
`description` text,
`updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
`created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY (`id`),
UNIQUE KEY `code_brand` (`code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;");

if (!se_db_is_field('shop_price','id_brand')){
   se_db_query("ALTER TABLE  `shop_price` CHANGE  `id_manufacturer`  `id_brand` INT( 10 ) UNSIGNED NULL DEFAULT NULL");
}
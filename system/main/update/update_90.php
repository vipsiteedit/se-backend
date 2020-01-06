<?php

se_db_query("CREATE TABLE IF NOT EXISTS `shop_feature_group` (
id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
id_main int(10) UNSIGNED NOT NULL DEFAULT 1,
name varchar(255) NOT NULL,
description text DEFAULT NULL,
image varchar(255) DEFAULT NULL,
sort int(10) NOT NULL DEFAULT 0,
updated_at timestamp DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
created_at timestamp DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY (id),
INDEX id_main (id_main),
INDEX sort (sort)
)
ENGINE = INNODB
AUTO_INCREMENT = 1
AVG_ROW_LENGTH = 5461
CHARACTER SET utf8
COLLATE utf8_general_ci;");

se_db_query("CREATE TABLE IF NOT EXISTS `shop_modifications_group` (
`id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
`id_main` int(10) UNSIGNED NOT NULL DEFAULT 1,
`name` varchar(50) NOT NULL,
`vtype` smallint(1) UNSIGNED DEFAULT 0,
`sort` int(10) DEFAULT 0,
`updated_at` timestamp DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
`created_at` timestamp DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY (id),
INDEX id_main(`id_main`),
INDEX sort(`sort`),
INDEX updated_at(`updated_at`)
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8
COLLATE utf8_general_ci;");

se_db_query("CREATE TABLE IF NOT EXISTS `shop_feature` (
`id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
`id_feature_group` int(10) UNSIGNED DEFAULT NULL,
`name` varchar(255) NOT NULL,
`type` enum ('list', 'colorlist', 'number', 'bool', 'string') DEFAULT 'list',
`image` varchar(255) DEFAULT NULL,
`measure` varchar(20) DEFAULT NULL,
`description` text DEFAULT NULL,
`sort` int(10) NOT NULL DEFAULT 0,
`updated_at` timestamp DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
`created_at` timestamp DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY (id),
INDEX id_mod_group (id_feature_group),
CONSTRAINT shop_feature_ibfk_1 FOREIGN KEY (id_feature_group)
REFERENCES shop_feature_group (id) ON DELETE CASCADE ON UPDATE CASCADE
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8
COLLATE utf8_general_ci;");


se_db_query("CREATE TABLE IF NOT EXISTS shop_modifications (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_mod_group` int(10) unsigned NOT NULL,
  `id_price` int(10) unsigned NOT NULL,
  `code` varchar(40) default NULL,
  `value` double(10,2) NOT NULL,
  `value_opt` double(10,2) NOT NULL,
  `value_opt_corp` double(10,2) NOT NULL,
  `count` int(10) DEFAULT NULL,
  `sort` int(10) NOT NULL DEFAULT '0',
  `default` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `id_exchange` varchar(40) DEFAULT NULL,
  `description` TEXT NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `id_mod_group` (`id_mod_group`),
  KEY `price_id` (`id_price`),
  KEY `sort` (`sort`),
  KEY `id_exchange` (`id_exchange`),
CONSTRAINT FK_shop_modifications_shop_modifications_group_id FOREIGN KEY (id_mod_group)
REFERENCES shop_modifications_group (id) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT shop_modifications_ibfk_1 FOREIGN KEY (id_price)
REFERENCES shop_price (id) ON DELETE CASCADE ON UPDATE CASCADE
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8
COLLATE utf8_general_ci;");


se_db_query("CREATE TABLE IF NOT EXISTS `shop_group_feature` (
id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
id_group int(10) UNSIGNED NOT NULL,
id_feature int(10) UNSIGNED NOT NULL,
sort int(10) NOT NULL DEFAULT 0,
updated_at timestamp DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
created_at timestamp DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY (id),
INDEX shop_group_filter_uk2 (id_group),
INDEX sort (sort),
CONSTRAINT FK_shop_group_feature_shop_feature_id FOREIGN KEY (id_feature)
REFERENCES shop_feature (id) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT FK_shop_group_feature_shop_group_id FOREIGN KEY (id_group)
REFERENCES shop_group (id) ON DELETE CASCADE ON UPDATE CASCADE
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8
COLLATE utf8_general_ci;");


se_db_query("CREATE TABLE IF NOT EXISTS shop_feature_value_list (
id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
id_feature int(10) UNSIGNED NOT NULL,
value varchar(255) NOT NULL,
color varchar(6) DEFAULT NULL,
sort int(10) NOT NULL DEFAULT 0,
`default` tinyint(1) NOT NULL DEFAULT 0,
updated_at timestamp DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
created_at timestamp DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY (id),
CONSTRAINT shop_feature_value_fk1 FOREIGN KEY (id_feature)
REFERENCES shop_feature (id) ON DELETE CASCADE ON UPDATE CASCADE
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8
COLLATE utf8_general_ci;");


se_db_query("CREATE TABLE IF NOT EXISTS `shop_group_filter` (
id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
id_group int(10) UNSIGNED NOT NULL,
id_feature int(10) UNSIGNED DEFAULT NULL,
default_filter enum ('price', 'brand', 'flag_hit', 'flag_new', 'discount') DEFAULT NULL,
expanded tinyint(1) DEFAULT 0,
sort int(10) NOT NULL DEFAULT 0,
updated_at timestamp DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
created_at timestamp DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY (id),
UNIQUE INDEX UK_shop_group_filter (id_feature, id_group),
UNIQUE INDEX UK_shop_group_filter2 (id_group, default_filter),
CONSTRAINT shop_group_filter_fk1 FOREIGN KEY (id_group)
REFERENCES shop_group (id) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT shop_group_filter_fk2 FOREIGN KEY (id_feature)
REFERENCES shop_feature (id) ON DELETE SET NULL ON UPDATE CASCADE
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8
COLLATE utf8_general_ci;");

se_db_query("CREATE TABLE IF NOT EXISTS `shop_modifications_img` (
id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
id_modification int(10) UNSIGNED NOT NULL,
id_img int(10) UNSIGNED NOT NULL,
sort int(11) NOT NULL DEFAULT 0,
updated_at timestamp DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
created_at timestamp DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY (id),
INDEX id_modification (id_modification),
INDEX sort (sort),
CONSTRAINT FK_shop_modifications_img_shop_img_id FOREIGN KEY (id_img)
REFERENCES shop_img (id) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT FK_shop_modifications_img_shop_modifications_id FOREIGN KEY (id_modification)
REFERENCES shop_modifications (id) ON DELETE CASCADE ON UPDATE CASCADE
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8
COLLATE utf8_general_ci;");


se_db_query("CREATE TABLE IF NOT EXISTS `shop_modifications_feature` (
id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
id_price int(10) UNSIGNED DEFAULT NULL,
id_modification int(10) UNSIGNED DEFAULT NULL,
id_feature int(10) UNSIGNED NOT NULL,
id_value int(10) UNSIGNED DEFAULT NULL,
value_number double DEFAULT NULL,
value_bool tinyint(1) DEFAULT NULL,
value_string varchar(255) DEFAULT NULL,
sort int(10) NOT NULL DEFAULT 0,
updated_at timestamp DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
created_at timestamp DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY (id),
INDEX id_modification (id_modification),
INDEX id_feature (id_feature),
INDEX id_price (id_price),
INDEX id_value (id_value),
UNIQUE INDEX shop_price_feature_uk1 (id_modification, id_price, id_feature, id_value),
CONSTRAINT FK_shop_modifications_feature_shop_price_id FOREIGN KEY (id_price)
REFERENCES shop_price (id) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT shop_modifications_feature_ibfk_1 FOREIGN KEY (id_modification)
REFERENCES shop_modifications (id) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT shop_price_feature_fk2 FOREIGN KEY (id_feature)
REFERENCES shop_feature (id) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT shop_price_feature_fk3 FOREIGN KEY (id_value)
REFERENCES shop_feature_value_list (id) ON DELETE SET NULL ON UPDATE CASCADE
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8
COLLATE utf8_general_ci;");

<?php
se_db_query("
ALTER TABLE  `shop_deliverytype` ADD  `city_from_delivery` VARCHAR( 128 ) NULL AFTER  `forone`
");

se_db_query("
ALTER TABLE  `shop_price` ADD  `is_market` BOOLEAN NOT NULL DEFAULT FALSE AFTER  `vizits` ,
ADD INDEX (  `is_market` )
");

se_db_query("
ALTER TABLE  `main` ADD  `shopname` VARCHAR( 255 ) NOT NULL AFTER  `lang`
");
se_db_query("
ALTER TABLE  `main` ADD  `subname` VARCHAR( 255 ) NOT NULL AFTER  `shopname`
");
se_db_query("
ALTER TABLE  `main` ADD  `logo` VARCHAR( 255 ) NOT NULL AFTER  `subname`
");

se_db_query("
ALTER TABLE  `main` ADD  `is_store` tinyint(1) NOT NULL DEFAULT 0 AFTER  `domain`
");

se_db_query("
ALTER TABLE  `main` ADD  `is_pickup` tinyint(1) NOT NULL DEFAULT 0 AFTER  `is_store`
");

se_db_query("
ALTER TABLE  `main` ADD  `is_delivery` tinyint(1) NOT NULL DEFAULT 1 AFTER  `is_pickup`
");

se_db_query("
ALTER TABLE  `main` ADD  `local_delivery_cost` DOUBLE NOT NULL DEFAULT  '0.00' AFTER  `is_delivery`
");

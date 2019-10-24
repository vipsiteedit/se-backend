<?php
se_db_query("
CREATE TABLE IF NOT EXISTS `shop_discounts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `action` enum('single','constant','increase','falling') NOT NULL DEFAULT 'single' COMMENT 'single - акция, constant - постоянная,increase - ростущий,falling - падающий',
  `step_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Шаг времени - в часах',
  `step_discount` double(10,3) NOT NULL DEFAULT '0.000' COMMENT 'Шаг дисконтирования',
  `date_from` varchar(19) DEFAULT NULL,
  `date_to` varchar(19) DEFAULT NULL,
  `summ_from` double(10,2) DEFAULT NULL,
  `summ_to` double(10,2) DEFAULT NULL,
  `count_from` int(11) DEFAULT '-1',
  `count_to` int(11) DEFAULT '-1',
  `discount` double(10,3) DEFAULT '5.000',
  `type_discount` enum('percent','absolute') NOT NULL DEFAULT 'percent',
  `week` char(7) DEFAULT NULL,
  `summ_type` int(10) unsigned DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `updated_at` (`updated_at`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");

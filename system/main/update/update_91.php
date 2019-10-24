<?php

se_db_query("CREATE TABLE IF NOT EXISTS `shop_reviews` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_price` int(10) unsigned NOT NULL,
  `id_user` int(10) unsigned NOT NULL,
  `mark` smallint(1) unsigned NOT NULL,
  `merits` text,
  `demerits` text,
  `comment` text NOT NULL,
  `use_time` smallint(1) unsigned NOT NULL DEFAULT '1',
  `date` datetime NOT NULL,
  `likes` int(10) unsigned NOT NULL DEFAULT '0',
  `dislikes` int(10) unsigned NOT NULL DEFAULT '0',
  `active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UK_shop_reviews` (`id_price`,`id_user`),
  KEY `FK_shop_reviews_se_user_id` (`id_user`),
  CONSTRAINT `FK_shop_reviews_se_user_id` FOREIGN KEY (`id_user`) 
  REFERENCES `se_user` (`id`),
  CONSTRAINT `FK_shop_reviews_shop_price_id` FOREIGN KEY (`id_price`) 
  REFERENCES `shop_price` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
");

se_db_query("CREATE TABLE IF NOT EXISTS `shop_reviews_votes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_review` int(10) unsigned NOT NULL,
  `id_user` int(10) unsigned NOT NULL,
  `vote` smallint(1) NOT NULL DEFAULT '1',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UK_shop_reviews_votes` (`id_review`,`id_user`),
  KEY `FK_shop_reviews_votes_se_user_id` (`id_user`),
  CONSTRAINT `FK_shop_reviews_votes_se_user_id` FOREIGN KEY (`id_user`) REFERENCES `se_user` (`id`),
  CONSTRAINT `FK_shop_reviews_votes_shop_reviews_id` FOREIGN KEY (`id_review`) REFERENCES `shop_reviews` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;");

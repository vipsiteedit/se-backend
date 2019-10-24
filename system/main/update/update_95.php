<?php

if (!se_db_is_field('shop_feature_value_list','image')){ 
    se_db_query("ALTER TABLE `shop_feature_value_list` ADD `image` varchar(255) DEFAULT NULL AFTER `color`");
}

if (!se_db_is_field('shop_feature','seo')){ 
    se_db_query("ALTER TABLE  `shop_feature` ADD `seo` tinyint(1) NOT NULL DEFAULT 1 AFTER  `sort`");
    se_db_query("ALTER TABLE `shop_feature` ADD INDEX (`seo`);");
}

<?php

if (!se_db_is_field('shop_price','step_count')){ 
    se_db_add_field('shop_price', 'step_count', "double(10,3) NOT NULL default 1.00 AFTER `presence_count`");
}

se_db_query("ALTER TABLE  `shop_tovarorder` CHANGE  `count`  `count` DOUBLE( 10, 3 ) UNSIGNED NOT NULL");
se_db_query("ALTER TABLE  `shop_modifications` CHANGE  `count`  `count` DOUBLE( 10, 3 ) NULL DEFAULT NULL");
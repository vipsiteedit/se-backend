<?php

if (!se_db_is_field('shop_group','filter')){ 
se_db_query("ALTER TABLE  `shop_group` ADD  `filter` TINYINT( 1 ) NOT NULL DEFAULT  '0' AFTER  `visits`");
}

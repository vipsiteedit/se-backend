<?php

if (!se_db_is_field('shop_group','compare')){ 
se_db_query("ALTER TABLE  `shop_group` ADD  `compare` TINYINT( 1 ) NOT NULL DEFAULT  '0' AFTER  `visits`");
}

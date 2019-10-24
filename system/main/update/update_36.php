<?php
if (!se_db_is_field('shop_discount','id_user')){ 
    se_db_add_field('shop_discount', 'id_user', "int unsigned default NULL AFTER `id_group`");
    se_db_query("ALTER TABLE `shop_discount` ADD INDEX (`id_user`)");
}
if (!se_db_is_field('shop_discount','priority')){ 
    se_db_add_field('shop_discount', 'priority', "smallint unsigned default NULL AFTER `id_user`");
}

se_db_query("ALTER TABLE`shop_discount` CHANGE `type` `type` enum('g','p','o','m','i') NOT NULL default 'm'");

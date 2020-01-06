<?php

if (!se_db_is_field('shop_discount','id_user')){
    se_db_add_field('shop_discount', 'id_user', "int(10) unsigned NOT NULL AFTER `id`");
    se_db_query('ALTER TABLE `shop_discount` ADD INDEX (`id_user`);');
}
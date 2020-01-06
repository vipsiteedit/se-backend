<?php

if (!se_db_is_field('shop_order','manager_id')) {
    se_db_add_field('shop_order', 'manager_id', "int unsigned default NULL AFTER `id`");
    se_db_query("ALTER TABLE `shop_order` ADD INDEX (`manager_id`)");
}
<?php

if (!se_db_is_field('shop_deliverytype','code')) {
    se_db_add_field('shop_deliverytype', 'code', "varchar(20) NULL AFTER `id`");
    se_db_query("ALTER TABLE `shop_deliverytype` ADD INDEX (`code`)");
}

if (!se_db_is_field('main','city_from_delivery')) {
    se_db_add_field('main', 'city_from_delivery', "varchar(40) NULL AFTER `domain`");
}
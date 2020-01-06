<?php

if (!se_db_is_field('shop_coupons_goods','coupon_id')){
    se_db_add_field('shop_coupons_goods', 'coupon_id', "int(10) unsigned NOT NULL AFTER `id`");
    se_db_query('ALTER TABLE `shop_coupons_goods` DROP FOREIGN KEY  `shop_coupons_goods_ibfk_1`;');
    se_db_query('ALTER TABLE `shop_coupons_goods` ADD INDEX (`coupon_id`);');
    se_db_query("ALTER TABLE `shop_coupons_goods` ADD CONSTRAINT `shop_coupons_goods_ibfk_1` FOREIGN KEY (`coupon_id`) REFERENCES `shop_coupons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;");
}
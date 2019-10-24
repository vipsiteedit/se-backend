<?php

if (!se_db_is_field('shop_coupons','payment_id')){ 
    se_db_add_field('shop_coupons', 'payment_id', "int(10) unsigned DEFAULT NULL AFTER `count_used`");
}

if (!se_db_is_field('shop_coupons','only_registered')){ 
    se_db_add_field('shop_coupons', 'only_registered', "enum('Y','N') NOT NULL DEFAULT 'N' AFTER `count_used`");
}

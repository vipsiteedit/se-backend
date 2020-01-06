<?php

if (!se_db_is_field('shop_deliverytype','time_to')){ 
    se_db_add_field('shop_deliverytype', 'time_to', "time DEFAULT NULL AFTER `note`");
}

if (!se_db_is_field('shop_deliverytype','time_from')){ 
    se_db_add_field('shop_deliverytype', 'time_from', "time DEFAULT NULL AFTER `note`");
}

if (!se_db_is_field('shop_deliverytype','week')){ 
    se_db_add_field('shop_deliverytype', 'week', "char(7) DEFAULT '1111111' AFTER `note`");
}

if (!se_db_is_field('shop_deliverytype','max_weight')){ 
    se_db_add_field('shop_deliverytype', 'max_weight', "float(10,3) unsigned DEFAULT NULL AFTER `note`");
}

if (!se_db_is_field('shop_deliverytype','max_volume')){ 
    se_db_add_field('shop_deliverytype', 'max_volume', "int(11) unsigned DEFAULT NULL AFTER `note`");
}

if (!se_db_is_field('shop_deliverytype','status')){ 
    se_db_add_field('shop_deliverytype', 'status', "enum('Y','N') DEFAULT 'Y' AFTER `forone`");
}

<?php
if (!se_db_is_field('shop_payment','way_payment')){ 
    se_db_add_field('shop_payment', 'way_payment', "enum('b','a') NOT NULL default 'b' AFTER `authorize`");
    se_db_query("ALTER TABLE  `shop_payment` ADD INDEX (`way_payment`);");
}

<?php
if (!se_db_is_field('shop_special','discount_step')){ 
    se_db_add_field('shop_special', 'discount_step', "tinyint(2) NOT NULL default '0' AFTER `status`");
}
if (!se_db_is_field('shop_special','time_step')){ 
    se_db_add_field('shop_special', 'time_step', "varchar(5) NOT NULL default '' AFTER `status`");
}

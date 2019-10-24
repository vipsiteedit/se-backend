<?php

if (!se_db_is_field('shop_comm','is_active')) {
    se_db_add_field('shop_comm', 'is_active', "enum('Y','N') default 'N' AFTER `mark`");
    se_db_query("ALTER TABLE `shop_comm` ADD INDEX (`is_active`)"); 
}
if (!se_db_is_field('shop_comm','showing')) {
    se_db_add_field('shop_comm', 'showing', "enum('Y','N') default 'N' AFTER `mark`");
    se_db_query("ALTER TABLE `shop_comm` ADD INDEX (`showing`)"); 
}

<?php
if (!se_db_is_field('shop_delivery','name_recipient')){ 
    se_db_add_field('shop_delivery', 'name_recipient', "varchar(150) NOT NULL default '' AFTER `id`");
}

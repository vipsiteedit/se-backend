<?php
if (!se_db_is_field('shop_price_param','imgparam')){ 
    se_db_add_field('shop_price_param', 'imgparam', "varchar(255) default NULL AFTER `count`");
}

if (!se_db_is_field('shop_price_param','imgparam_alt')){ 
    se_db_add_field('shop_price_param', 'imgparam_alt', "varchar(255) default NULL AFTER `imgparam`");
}
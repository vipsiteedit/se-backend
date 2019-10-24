<?php

if (!se_db_is_field('shop_tovarorder','modifications')){
    se_db_add_field('shop_tovarorder', 'modifications', "varchar(255) default NULL AFTER `count`");
}
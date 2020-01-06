<?php
if (!se_db_is_field('shop_payment','url_help')){ 
    se_db_add_field('shop_payment', 'url_help', "varchar(255) default NULL AFTER `authorize`");
}
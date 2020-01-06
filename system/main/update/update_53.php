<?php
if (!se_db_is_field('main','shopname')){ 
    se_db_add_field('main', 'shopname', "varchar(255) default NULL AFTER `lang`");
}

if (!se_db_is_field('shop_payment','ident')){ 
    se_db_add_field('shop_payment', 'ident', "varchar(40) default NULL AFTER `lang`");
}

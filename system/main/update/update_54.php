<?php

if (!se_db_is_field('shop_payment','is_test')){ 
    se_db_add_field('shop_payment', 'is_test', "enum('Y','N') default 'N' AFTER `active`");
}

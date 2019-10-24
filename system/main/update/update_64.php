<?php

if (!se_db_is_field('shop_price','descriiption')){ 
    se_db_add_field('shop_price', 'description', "text DEFAULT NULL AFTER `keywords`");
}


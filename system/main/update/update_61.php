<?php

if (!se_db_is_field('shop_deliverytype','need_address')){ 
    se_db_add_field('shop_deliverytype', 'need_address', "enum('Y','N') DEFAULT 'Y' AFTER `note`");
}

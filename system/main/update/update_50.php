<?php
if (!se_db_is_field('shop_param','lang')){ 
    se_db_add_field('shop_param', 'lang', "char(3) NOT NULL default 'rus' AFTER `id`");
    se_db_query("ALTER TABLE  `shop_param` ADD INDEX (`lang`);");
}

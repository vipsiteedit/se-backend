<?php

if (!se_db_is_field('shop_img','picture_alt')){ 
    se_db_query("ALTER TABLE  `shop_img` ADD  `picture_alt` varchar(255) DEFAULT NULL AFTER  `picture`");
    //se_db_add_field('shop_img', 'picture_alt', "picture_alt varchar(255) DEFAULT NULL AFTER  `picture`");
}

if (!se_db_is_field('shop_img','sort')){ 
    se_db_query("ALTER TABLE  `shop_img` ADD  `sort` int(11) DEFAULT 0 AFTER  `picture`");
    //se_db_add_field('shop_img', 'sort', "sort int(11) NOT NULL DEFAULT 0 AFTER  `title`");
}

if (!se_db_is_field('shop_img','`default`')){
    se_db_query("ALTER TABLE  `shop_img` ADD  `default` BOOLEAN NOT NULL DEFAULT FALSE AFTER  `title`");
        //se_db_add_field('shop_img', '`default`', "`default` tinyint(1) NOT NULL DEFAULT 0 AFTER  `title`");
}

if (!se_db_is_field('shop_modifications_img','sort')){ 
    se_db_query("ALTER TABLE  `shop_modifications_img` ADD  `sort` int(11) NOT NULL DEFAULT 0 AFTER  `id_img`");
    //se_db_add_field('shop_modifications_img', 'sort', "sort int(11) NOT NULL DEFAULT 0 AFTER  `id_img`");
}

<?php

if (!se_db_is_field('shop_order','delivery_service_name')){ 
    se_db_add_field('shop_order', 'delivery_service_name', "varchar(255) default NULL AFTER `id_main`");
}

if (!se_db_is_field('shop_order','delivery_doc_date')){ 
    se_db_add_field('shop_order', 'delivery_doc_date', "date default NULL AFTER `id_main`");
}

if (!se_db_is_field('shop_order','delivery_doc_num')){ 
    se_db_add_field('shop_order', 'delivery_doc_num', "varchar(40) default NULL AFTER `id_main`");
}

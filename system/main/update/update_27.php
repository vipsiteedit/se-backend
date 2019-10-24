<?php

     if (!se_db_is_field('shop_mail','shop_mail_group_id')) se_db_add_field('shop_mail', 'shop_mail_group_id', 'int(10) unsigned default 1 AFTER `id`');
     if (!se_db_is_field('shop_group','visits')) se_db_add_field('shop_group', 'visits', 'int(10) unsigned NOT NULL default 0 AFTER `active`');
     if (!se_db_is_field('shop_group','picture_alt')) se_db_add_field('shop_group', 'picture_alt', 'varchar(255) default NULL AFTER `picture`');
     if (!se_db_is_field('shop_group','keywords')) se_db_add_field('shop_group', 'keywords', 'varchar(255) default NULL AFTER `commentary`');
     if (!se_db_is_field('shop_group','title')) se_db_add_field('shop_group', 'title', 'varchar(255) default NULL AFTER `commentary`');
     if (!se_db_is_field('shop_group','footertext')) se_db_add_field('shop_group', 'footertext', 'text default NULL AFTER `commentary`');


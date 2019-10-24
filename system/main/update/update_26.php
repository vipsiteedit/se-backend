<?php

      se_table_migration('se_user_account');
      se_table_migration('se_account_operation');
      se_table_migration('shop_group_price');
      se_table_migration('shop_manufacturer');
      se_table_migration('shop_order_action');
      se_table_migration('shop_mail_group');

      se_db_query("ALTER TABLE`shop_group` CHANGE `picture` `picture` VARCHAR(255) DEFAULT NULL");
     // se_db_query("ALTER TABLE`shop_group` CHANGE `picture_alt` `picture` VARCHAR(255) DEFAULT NULL");
      se_db_query("ALTER TABLE `shop_img` CHANGE `picture` `picture` VARCHAR(255) DEFAULT NULL");
      se_db_query("ALTER TABLE `shop_price` CHANGE `img` `img` VARCHAR(255) DEFAULT NULL");
      se_db_query("ALTER TABLE `shop_group` CHANGE `code_gr` `code_gr` VARCHAR(40) NOT NULL"); 

     if (!se_db_is_field('shop_price','img_alt')) se_db_add_field('shop_price', 'img_alt', 'varchar(255) default NULL AFTER `img`');
     if (!se_db_is_field('shop_price','keywords')) se_db_add_field('shop_price', 'keywords', 'varchar(255) default NULL AFTER `text`');
     if (!se_db_is_field('shop_price','title')) se_db_add_field('shop_price', 'title', 'varchar(255) default NULL AFTER `text`');
     if (!se_db_is_field('shop_price','id_manufacturer')) se_db_add_field('shop_price', 'id_manufacturer', 'int(10) unsigned default NULL AFTER `manufacturer`');
     if (!se_db_is_field('shop_price','flag_new')) se_db_add_field('shop_price', 'flag_new',"enum('Y','N') default 'N' AFTER `discount`");
     if (!se_db_is_field('shop_price','flag_hit')) se_db_add_field('shop_price', 'flag_hit',"enum('Y','N') default 'N' AFTER `flag_new`");
     if (!se_db_is_field('shop_price','unsold')) se_db_query("ALTER TABLE `shop_price` ADD `unsold` ENUM( 'Y', 'N' ) NOT NULL DEFAULT 'N' AFTER `enabled`,ADD INDEX ( `unsold` )");
     if (!se_db_is_field('shop_price','votes')) se_db_query("ALTER TABLE `shop_price` ADD `votes` int(11) NOT NULL DEFAULT '0' AFTER `enabled`,ADD INDEX ( `votes` )");


     if (!se_db_is_field('shop_payment','filters')) se_db_add_field('shop_payment', 'filters', "text AFTER `sort`");
     if (!se_db_is_field('shop_payment','hosts')) se_db_add_field('shop_payment', 'hosts', "text AFTER `filters`");
     if (!se_db_is_field('shop_payment','authirize')) se_db_add_field('shop_payment', 'authorize', "enum('Y','N') AFTER `hosts`");

     if (!se_db_is_field('main','domain')) se_db_add_field('main', 'domain', 'varchar(250) AFTER `basecurr`');
     if (!se_db_is_field('shop_deliverytype','forone')) se_db_add_field('shop_deliverytype', 'forone',"ENUM('Y','N') DEFAULT 'N' AFTER `weight`");
     if (!se_db_is_field('privileges','group_id')) se_db_add_field('privileges', 'group_id',"int(10) unsigned default NULL AFTER `id_useradmin`");
     if (!se_db_is_field('shop_order','is_delete')) 
     {
        se_db_add_field('shop_order', 'is_delete',"enum('N','Y') default 'N' AFTER `inpayee`");
    	se_db_query("ALTER TABLE `shop_order` ADD INDEX (`is_delete`);");
     }


     if (!se_db_is_field('person','skype')) se_db_add_field('person', 'skype', 'varchar(125) AFTER `icq`');
     if (!se_db_is_field('person','reg_info')) se_db_add_field('person', 'reg_info', 'varchar(255) AFTER `skype`');
     if (!se_db_is_field('person','note')) se_db_add_field('person', 'note', 'text AFTER `reg_info`');
     if (!se_db_is_field('person','manager_id')) se_db_add_field('person', 'manager_id', 'int(10) unsigned default NULL AFTER `note`');
     if (!se_db_is_field('person','loyalty')) se_db_add_field('person', 'loyalty', 'smallint(6) default 5 AFTER `manager_id`');
     if (!se_db_is_field('person','avatar')) se_db_add_field('person', 'avatar', 'varchar(125) AFTER `loyalty`');

     if (!se_db_is_field('privileges','pmain')) se_db_add_field('privileges', 'pmain', "enum('Y','N') default 'Y' AFTER `pread`");

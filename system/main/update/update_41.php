<?php
      se_db_query("ALTER TABLE`shop_order` CHANGE `is_delete` `is_delete` enum('N', 'Y') DEFAULT 'N'");
      se_db_query("ALTER TABLE `shop_order` ADD INDEX (`is_delete`)");
      se_db_query("UPDATE `shop_order` SET `is_delete`='N' WHERE `is_delete` IS NULL");

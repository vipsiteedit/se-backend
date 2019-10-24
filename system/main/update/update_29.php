<?php
      se_db_query("ALTER TABLE`shop_order` CHANGE `delivery_status` `delivery_status` enum('N', 'Y', 'P', 'M') DEFAULT 'N'");

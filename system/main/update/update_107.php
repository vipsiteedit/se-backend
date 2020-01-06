<?php
se_db_query("ALTER TABLE `shop_order` CHANGE `delivery_payee` `delivery_payee` DOUBLE(10,2) UNSIGNED NOT NULL DEFAULT '0.00';");

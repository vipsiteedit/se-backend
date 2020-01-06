<?php

se_db_query("ALTER TABLE  `shop_order` CHANGE  `status`  `status` ENUM(  'Y',  'N',  'K',  'P',  'W', 'T' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  'N'");
se_db_query("ALTER TABLE  `shop_order` CHANGE  `date_credit`  `date_credit` DATETIME NULL DEFAULT NULL");
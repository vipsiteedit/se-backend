<?php
se_db_query("ALTER TABLE  `shop_payment` CHANGE  `blank`  `blank` MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL");
se_db_query("ALTER TABLE  `shop_payment` CHANGE  `result`  `result` MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL");

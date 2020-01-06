<?php

se_db_query("ALTER TABLE  `shop_payment` CHANGE  `active`  `active` ENUM(  'N',  'Y',  'T' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT  'N'");

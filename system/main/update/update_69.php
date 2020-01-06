<?php

se_db_query("ALTER TABLE  `shop_price_param` ADD  `vtype` ENUM(  'add',  'calc' ) NOT NULL DEFAULT  'add' AFTER  `imgparam_alt`;");

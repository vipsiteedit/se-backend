<?php

se_db_query("ALTER TABLE  `shop_price` CHANGE  `presence_count`  `presence_count` DOUBLE( 10, 3 ) NULL DEFAULT  '-1.000'");
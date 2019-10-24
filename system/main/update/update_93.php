<?php

se_db_query("ALTER TABLE  `money` CHANGE  `kurs`  `kurs` DOUBLE( 20, 6 ) NOT NULL");

if (!se_db_is_field('money_title','minsum')){
   se_db_query("ALTER TABLE  `money_title` ADD  `minsum` DOUBLE( 10, 2 ) NOT NULL DEFAULT  '0.01' AFTER  `cbr_kod`");
}

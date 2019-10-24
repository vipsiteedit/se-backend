<?php

if (!se_db_is_field('news','send_letter')){ 
se_db_query("ALTER TABLE  `news` ADD  `send_letter` ENUM(  'Y',  'N' ) NOT NULL DEFAULT  'N' AFTER  `active` ,
ADD INDEX (  `send_letter` )");
}

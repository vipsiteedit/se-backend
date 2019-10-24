<?php
se_db_query("
   ALTER TABLE `session` CHANGE `TIMES` `TIMES` INT( 11 ) NULL DEFAULT NULL;
");

if(!function_exists('se_db_is_index') ||!se_db_is_index('session', 'SID')){
    se_db_query("ALTER TABLE `session` ADD PRIMARY KEY ( `SID` )");
}

if(!function_exists('se_db_is_index') || !se_db_is_index('session', 'TIMES')){
  se_db_query("ALTER TABLE `session` ADD INDEX ( `TIMES` )");
}

if(!function_exists('se_db_is_index') ||!se_db_is_index('session', 'IDUSER')){
   se_db_query("ALTER TABLE `session` ADD INDEX ( `IDUSER` )");
}

if(!function_exists('se_db_is_index') ||!se_db_is_index('session', 'GROUPUSER')){
    se_db_query("ALTER TABLE `session` ADD INDEX ( `GROUPUSER` )");
}


if(!function_exists('se_db_is_index') ||!se_db_is_index('session', 'IP')){
   se_db_query("ALTER TABLE `session` ADD INDEX ( `IP` )");
}

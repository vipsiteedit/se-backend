#! /usr/bin/php
<?php

function getDump() {
    $path = __DIR__ . '/../';
    if (!file_exists($path . "/public_html/system/config_db.php")) return;
	include $path . "/public_html/system/config_db.php";

	$username = $CONFIG['DBUserName'];
	$password = $CONFIG['DBPassword'];
	$hostname = $CONFIG['HostName'];
	$database = $CONFIG['DBName'];
	
	$backupFile = __DIR__ . '/dump.sql';
	$command = "mysqldump -u'{$username}' -p'{$password}' -h$hostname $database > $backupFile";
	system($command, $result);
}

function setDump() { 
    $path = __DIR__ . '/../';
    if (!file_exists($path . "/public_html/system/config_db.php")) { echo "Нет файла ".$path . "/public_html/system/config_db.php\n"; return; }
	include $path . "/public_html/system/config_db.php";

	$username = $CONFIG['DBUserName'];
	$password = $CONFIG['DBPassword'];
	$hostname = $CONFIG['HostName'];
	$database = $CONFIG['DBName'];
        $backupFile = __DIR__ . '/dump.sql';
	if (file_exists($backupFile)) {
	   $command = "mysql -u'{$username}' -p'{$password}' -h$hostname --default-character-set=utf8 $database < $backupFile";
       system($command, $result);
	  rename($backupFile, __DIR__ . '/dump.sql_'); 
	}
} 

//getDump();
setDump();
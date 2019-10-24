#! /usr/local/bin/php
<?php
function install_schema()
{
return;
	$root = getcwd().'/../../';
	require_once $root."system/conf_mysql.php";
	require_once $root."lib/yaml/seYaml.class.php";
	require_once $root."lib/lib_database.php";
	se_db_dsn('mysql');
	se_db_connect();
	

       $table = 'se_group';
       //se_db_to_yaml($table, 'schema/'.$table.'.yml');
	//se_yaml_to_sql('schema/schema.yml', 'sql/');
	se_table_migration($table);
}

install_schema();
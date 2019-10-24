#! /usr/local/php5/bin/php
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
	
	$tables = seYAML::Load('tables.yml');
	$dir = 'schema';
	//if (chdir($dir)) {
	$tablelist = $tables['tables'];
	$i = -1;
        se_db_query("SET FOREIGN_KEY_CHECKS=0;");
    	foreach($tablelist as $id=>$table)
	{
	    echo $table . "\n";
	    $i++;
	    se_yaml_to_sql('schema/'.$table.'.yml', 'sql/');
	    se_table_migration($table);
	    //echo "DROP TABLE `{$table}_tmp`\n";
	    se_db_query("DROP TABLE `{$table}_tmp`");
	}

	echo "\n\n";	
	for($j = $i; $j >= 0; $j--)
	{
	    $table = $tablelist[$j];
	    echo "DROP TABLE `{$table}_tmp`\n";
	    se_db_query("DROP TABLE `{$table}_tmp`");
	}
        se_db_query("SET FOREIGN_KEY_CHECKS=1;");
	echo "end\n";
}

install_schema();
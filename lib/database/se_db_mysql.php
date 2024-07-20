<?php

/*
lib_database.php,v 8.12 2006/02/04

EDGESTILE SiteEdit,
http://www.edgestile.com

Copyright (c) 2006 EDGESTILE
*/
require_once dirname(__file__) . '/parser/yaml_mysql.php';
require_once dirname(__file__) . '/se_db_mysqlcache.php';
define('DB_CACHED', false); // Включить кэширование

if (!function_exists('get_magic_quotes_gpc')) {
function get_magic_quotes_gpc()
{
    // Check if magic quotes GPC emulation is needed
    if (version_compare(PHP_VERSION, '5.4.0', '<')) {
        return (bool) ini_get('magic_quotes_gpc');
    } else {
        return false; // Magic quotes GPC is deprecated and not available
    }
}
}


function se_db_connect($CONF = array(), $link = 'db_link')
{
  global $$link, $MEM, $CONFIG;
  $CONFIG = (!empty($CONF)) ? $CONF : $CONFIG;
  if (!empty($CONFIG)){
        $server = $CONFIG['HostName'];
        $username = $CONFIG['DBUserName'];
        $password = $CONFIG['DBPassword'];
        $database = $CONFIG['DBName'];
		if (!defined('DB_NAME'))
			define('DB_NAME', $database);
  }
  $$link = mysqli_connect($server, $username, $password, $database);
  mysqli_query($$link, "set character_set_client='UTF8'");
  mysqli_query($$link,"set character_set_results='UTF8'");
  mysqli_query($$link,"set collation_connection='UTF8_general_ci'");
  mysqli_query($$link,"set sql_mode=''");
  if ($$link)
    if (DB_CACHED && class_exists('Memcache')){
        $MEM = new Memcache;
        $MEM->pconnect('127.0.0.1', 11211);
    }    
  return $$link;
}

function se_db_close($link = 'db_link')
{
  global $$link, $MEM;
  if (DB_CACHED && !empty($MEM)) $MEM->close();
  return mysqli_close($$link);
}

function se_db_is_correct($table){
    $correcttable = file(dirname(__file__)."/correcttable.dat");
    foreach($correcttable as $line){
	if (empty($line)) continue;
	list($fundtable) = explode("|", $line, 1);
	if ($fundtable == $table) return true;
    }
    return false;
}


function se_db_input($string, $link = 'db_link')
{
  global $$link;
  if (get_magic_quotes_gpc()){
        $string = stripslashes($string);
  }
  	if (function_exists('mysqli_real_escape_string'))
  	{
    	return mysqli_real_escape_string($$link,$string ?? '');
  	} else
	if (function_exists('mysqli_escape_string'))
  	{
    	return mysqli_escape_string($string);
  	} else return htmlspecialchars($string);
}


function se_db_output($string)
{
  return htmlspecialchars(stripslashes($string));
}

// �������� �� ������������� ���� � �������
function se_db_is_field($table, $field, $link = 'db_link')
{
  global $$link;

  $aresult = mysqli_query($$link,"SHOW COLUMNS FROM `$table` WHERE Field='$field'");
  return (!empty($aresult)) ? mysqli_num_rows($aresult) : false;
}

function se_db_add_index($table, $field_name, $index = 1, $link='db_link'){
  global $$link;
    if (!se_db_is_index($table, $field_name)){
        $index = ($index == 1) ? 'INDEX' : 'UNIQUE';
        mysqli_query($$link,"ALTER TABLE `{$table}` ADD {$index}(`{$field_name}`);");
    }
}

function se_db_is_index($table, $field_name, $name_index = '', $link='db_link'){
    global $$link;
    $key_index = ($name_index) ? " AND `Key_name`='{$name_index}'" : '';
    $aresult = mysqli_query($$link,"SHOW INDEX FROM `{$table}` WHERE `Column_name` = '{$field_name}'".$key_index);
    $ares = (!empty($aresult)) ? mysqli_num_rows($aresult) : false;
    return $ares;
    
}

// �������� ������ ���� � �������
function se_db_add_field($table, $field, $type = 'varchar(20)', $link = 'db_link')
{
  global $$link;
  	$type = str_replace(array('integer', 'string', 'integer(2)', 'integer(4)', 'bool', 'boolean'), 
					array('int', 'varchar', 'int', 'bigint', 'tinyint(1)', 'tinyint(1)'), $type);
	if (preg_match("/float(\([\d\,]+\))?/u", $type, $m)){
		$m[1] = preg_replace("/[\(\)]/", '', $m[1]);
		if (!empty($m[1])){
			list($dec,) = explode(',', $m[1]);
			if (floatval($dec) < 8) $newType = 'float('.$m[1].')';
			else $newType = 'double('.$m[1].')';
		} else $newType = 'double(10,2)';
		$type = str_replace($m[0], $newType, $type);
	}
	$after = '';
	$fields = se_db_columns_field($table, $link);
	foreach($fields as $fld) {
		if ($fld == 'updated_at' || $fld == 'created_at'){
			break;
		} else 
			$after = $fld;
	}
	if ($after) {
		$after = " AFTER `{$after}`";
	}
	//echo "ALTER TABLE `{$table}` ADD `{$field}` {$type}{$after};";
  	mysqli_query($$link,"ALTER TABLE `{$table}` ADD `{$field}` {$type}{$after};");
}

function se_db_delete_item($table, $id, $link = 'db_link'){
    global $$link, $MEM;
	$correcttable = file(dirname(__file__)."/correcttable.dat");
	mysqli_query($$link,"DELETE FROM `$table` WHERE id=$id");
	foreach($correcttable as $line){
	    if (empty($line)) continue;
		$arr = explode("|", $line);
		if ($arr[0] == $table){
			$field = trim($arr[2]);
			if (trim($arr[3]) == '*')
				mysqli_query($$link,"UPDATE `$arr[1]` SET `$field` = NULL WHERE `$field` = $id");
			else {
				$query = mysqli_query($$link,"SELECT `id` FROM `$arr[1]` WHERE $field = $id");
				while ($l = mysqli_fetch_row($query))
					se_db_delete_item($arr[1], $l[0]);
			}
		}
	}
    if (DB_CACHED && !empty($MEM)) $MEM->flush();
}

function se_db_delete($table, $where = '', $log_upgate=true, $link = 'db_link'){
    global $$link, $MEM;
    if (DB_CACHED && !empty($MEM)) $MEM->flush();
    if (se_db_is_correct($table)){
	$res = mysqli_query($$link,"SELECT id FROM $table WHERE $where;");
	if (!empty($res)){
	    while (@$id_rec = mysqli_fetch_row($res)){
		se_db_delete_item($table, $id_rec[0]);
		if ($log_upgate) log_update($table, $id_rec[0], 'd');
	    }	
	    return true;
	}
    } else {
	$res = mysqli_query($$link,"SELECT id FROM $table WHERE $where;");
	if (!empty($res)){
	    while (@$id_rec = mysqli_fetch_row($res)){
		if ($log_upgate) log_update($table, $id_rec[0], 'd');
	    }
	    return mysqli_query($$link,"DELETE FROM $table WHERE $where");
	}
    }
    return false;
}

function se_db_perform_restrict($table, $data){
	$correcttable = file(dirname(__file__)."/correcttable.dat");
	foreach($correcttable as $line){
	    if (trim($line)=='') continue;
	    $line = explode("|", $line);
	    if (trim($line[1]) == $table){
	        $id = $data[trim($line[2])];
		if ($id == '' || $id == 'null'){
		    return true;
		}
		if ($id){
		    if (se_db_is_item(trim($line[0]), "id={$id}")){
			return true;
		    }
		}
		return false;
	    }
	}
	return true;
}

function se_db_InsertList($table, $data,  $link = 'db_link')
{
    global $$link;
    reset($data);
	if (!se_db_perform_restrict($table, $data)) return false;
    $query = 'insert into ' . $table . ' (';
    while (list($columns, ) = each($data[0]))
    {
      $columns = str_replace('`', '', $columns);
      $query .= '`' . str_replace('`', '', $columns) . '`, ';
    }
    $query = substr($query, 0, -2) . ') values ';
    reset($data);
    foreach($data as $item){
        $query .= '(';
        while (list($field, $value) = each($item))
        {
            $value = str_replace('\r\n', "\r\n", $value);
            if (empty($value))
                $value = '';
            switch ((string )$value){
              case 'now()':
                 $query .= 'now(), ';
                 break;
              case 'null':
                 $query .= 'null, ';
                 break;
              default:
                $query .= '\'' . se_db_input($value) . '\', ';
              break;
            }
        }
        $query = substr($query, 0, -2) . '),';
    }
    $query = substr($query, 0, -1) . ';';
    //log_write($query);
    $result = mysqli_query($$link,$query);
    if (DB_CACHED && !empty($MEM)) $MEM->flush();
  return $result; //mysql_query($query,$$link);
}

function se_db_error($link = 'db_link')
{
    global $$link;
	return mysqli_error($$link);
}


function se_db_perform($table, $data, $action = 'insert', $where = '', $log_update = true,  $link = 'db_link')
{
    global $$link;
    reset($data);
    if ($action == 'insert'){
	if (!se_db_perform_restrict($table, $data)) return false;
    //if (!se_db_is_field($table, 'updated_at'))
    //{
    //  se_db_add_field($table, 'updated_at', 'TIMESTAMP');
    //}

    //if (!se_db_is_field($table, 'created_at'))
    //{
    //  se_db_add_field($table, 'created_at', 'TIMESTAMP');
    //}
	
	
    $query = 'INSERT INTO ' . $table . ' (';
    foreach ($data as $columns => $value)
    {
      $value = str_replace('\r\n', "\r\n", $value);
      $columns = str_replace('`', '', $columns);
	  //if (!empty($value)){
         $query .= '`' . str_replace('`', '', $columns) . '`, ';
	  //}
    }
    $query = substr($query, 0, -2) . ') VALUES (';
    reset($data);
    foreach($data as $field => $value)
    {
      $value = str_replace('\r\n', "\r\n", $value);
      //if (empty($value)) continue;
       // $value = '';
      switch ((string)$value)
      {
        case 'now()':
          $query .= 'NOW(), ';
          break;
        case 'null':
          $query .= 'NULL, ';
          break;
        default:
          $query .= '\'' . se_db_input($value) . '\', ';
          break;
      }
    }
    $query = substr($query, 0, -2) . ')';

    $result = mysqli_query($$link, $query);
    if (DB_CACHED && !empty($MEM)) $MEM->flush();


    if ($result)
    {
      if (isset($data['id'])){
         $res = $data['id'];
      } else {
         list($res) = mysqli_fetch_row(mysqli_query($$link,"SELECT LAST_INSERT_ID()"));
      }
      if ($log_update)
      {
        log_update($table, $res, 'i');
      }
      return $res;
    }
    else
    {
      //echo mysql_error() . '<br>' . $query;
    }

    //  return $result;
  } elseif ($action == 'update')
  {
    $query = 'UPDATE ' . $table . ' SET ';
    foreach ($data as $columns => $value)
    {
      $columns = str_replace('`', '', $columns);
      $value = str_replace('\r\n', "\r\n", $value);

      if (empty($value))
        $value = '';

      switch ((string )$value)
      {
        case 'now()':
          $query .= '`' . $columns . '` = NOW(), ';
          break;
        case 'null':
          $query .= '`' . $columns . '` = NULL, ';
          break;
        default:
          $query .= '`' . $columns . '` = \'' . se_db_input($value) . '\', ';
          break;
      }
      // log_update($table,$id_rec[0],'d');
      //updates_wlog("u",$table,$where);
    }
    $query = substr($query, 0, -2) . ' where ' . $where;
    $result = mysqli_query($$link,$query);
    if (DB_CACHED && !empty($MEM)) $MEM->flush();

      if ($log_update)
      {
         $res = mysqli_query($$link,"select id from $table where $where;");
        if (!empty($res))
        while (@$id_rec = mysqli_fetch_array($res))
          log_update($table, $id_rec[0], 'u');
      }

    //return $result;
  }

  return $result; //mysql_query($query,$$link);
}

function se_db_is_item($table, $where, $link = 'db_link')
{
  global $$link;
  return (@mysqli_num_rows(mysqli_query($$link,"SELECT * FROM $table WHERE $where")) > 0);
}

function se_db_insert_id($table, $link = 'db_link')
{
  global $$link;
  //$id=mysql_fetch_array(mysql_query("SELECT LAST_INSERT_ID();",$$link));
  $id = mysqli_fetch_array(mysqli_query($$link,"SELECT max(id) from $table"));
  return $id[0];
}

function se_db_limit($offset = 0, $limit = 10000)
{
  if ($offset > 0)
    $limit = ' LIMIT ' . $offset . ',' . $limit;
  else
    $limit = ' LIMIT ' . $limit;

  return $limit;
}

function se_db_query($sql, $cashetime = 30, $link = 'db_link')
{
  global $$link, $MEM;

  if (preg_match("/DELETE FROM(.+?)WHERE([\w\W\S\s\d]{1,})/im", $sql, $res_math))
  {
    $table = trim(str_replace('`', '', @$res_math[1]));
    if ($table != 'session' && $table != 'se_search')
    {
      $where = @$res_math[2];
      $res = mysqli_query($$link,"SELECT id FROM $table WHERE $where");
      if (!empty($res) && DB_CACHED && !empty($MEM)) $MEM->flush();
      if (!empty($res))
        while (@$id_rec = mysqli_fetch_array($res))
          log_update($table, $id_rec[0], 'd');
    }
  }

  if (DB_CACHED && preg_match("/^SELECT/i", $sql) && ($cashetime>0)){
      return new MySQLCache($sql, $cashetime, $link); //, $link
  } else {
      $result = @mysqli_query($$link, $sql);
  }

  if (preg_match("/INSERT INTO(.+?)[\W]{1,}\(/im", $sql, $res_math))
  {
    if (DB_CACHED && !empty($MEM)) $MEM->flush();
    $table = trim(str_replace('`', '', @$res_math[1]));
    if ($table != 'session')
    {
      $res = mysqli_fetch_array(mysqli_query($$link,"SELECT LAST_INSERT_ID()"));
      log_update($table, $res[0], 'i');
    }
  }
  if (preg_match("/UPDATE(.+?)SET[\w\W]{1,}where([\w\W\S\s\d]{1,})/im", $sql, $res_math))
  {
    if (DB_CACHED && !empty($MEM)) $MEM->flush();
    $table = trim(str_replace('`', '', @$res_math[1]));
    $table = trim(str_replace(' ', '', $table));
    if ($table != 'session')
    {
      $where = @$res_math[2];
      $res = mysqli_query($$link,"SELECT id FROM $table WHERE $where");
      if (!empty($res))
        while (@$id_rec = mysqli_fetch_array($res))
          log_update($table, $id_rec[0], 'u');
    }
  }
  return $result;
}

function se_db_columns_field($table, $link = 'db_link'){
  global $$link;
  	$result = array();
	$q = mysqli_query($$link,"SHOW COLUMNS FROM `{$table}`");
    while ($value = mysqli_fetch_assoc($q)){
    	$result[] = $value['Field'];
    }
    return $result;
}

function se_db_fetch_array(&$query){
  if (!empty($query->ResultData))
  return $query->fetch_array();
  else return @mysqli_fetch_array($query);
}

function se_db_num_rows(&$query){
  if (!empty($query->ResultData))
      return $query->num_rows();
  else return (@mysqli_num_rows($query));
}

function se_db_fetch_row(&$query){
  if (!empty($query->ResultData))
  return $query->fetch_row();
  else return mysqli_fetch_row($query);
}

function se_db_fetch_assoc(&$query){
  if (!empty($query->ResultData))
  return $query->fetch_assoc();
else return mysqli_fetch_assoc($query);
}

function se_db_fields($razdel, $table, $where, $select, $fieldreplace = '', $link = 'db_link')
{
  global $obj, $object_extern, $table_field_error, $tablevalues, $$link;
  $tablevalues = "";
  $fieldrepl = explode(";", $fieldreplace);
  if ($result = mysqli_query($$link,"SELECT $select FROM $table WHERE $where"))
  {
    $count = mysqli_num_rows($result);
    for ($nn = 0; $nn < $count; $nn++)
    {
      $tabl = mysqli_fetch_array($result);
      $j = 0;
      while (@$linerepl = $fieldrepl[$j])
      {
        $linerepl = explode(",", $linerepl);
        if (@$tabl[$linerepl[0]] == @$linerepl[1])
          @$tabl[$linerepl[0]] = $linerepl[2];
        $j++;
      }

      $tablevalues = $tabl;
      $obj[$razdel][$nn] = replace_obj(0, $tabl, $razdel, $nn, "", "");
    }
  }
  else
    $table_field_error = "������ ������ �������";

  $object_extern[$razdel] = true;
}


function se_db_fields_list($table, $where, $select, $maska, $itemtext = "", $itemfield = -1, $fieldreplace = "", $styles = "", $link = 'db_link')
{
  global $$link, $table_field_error, $itemselect;
  $tabvalue = "";
  $fieldrepl = explode(";", $fieldreplace);
  $itemselect = -1;
  $mstyles = array();
  if (!empty($styles))
    $mstyles = explode(",", $styles);
  $cnstyle = count($mstyles);

  if ($result = mysqli_query($$link,"SELECT $select FROM $table WHERE $where"))
  {
    $nn = 0;
    $cn = 0;
    while ($tabl = mysqli_fetch_array($result))
    {
      $j = 0;
      if ($fieldreplace != "")
        while (@$linerepl = $fieldrepl[$j])
        {
          $linerepl = explode(",", $linerepl);
          @$tabl[$linerepl[0]] = str_replace($linerepl[1], $linerepl[2], $tabl[$linerepl[0]]);
          $j++;
        }


      $fmas = $maska;
      if ($cn == $cnstyle)
        $cn = 0;
      @$getstyle = $mstyles[$cn];
      $cn++;

      if ((@$itemfield >= 0) && ($itemtext == @$tabl[$itemfield]))
      {
        $select = "SELECTED";
        $itemselect = @$tabl[$itemfield];
      }
      else
        $select = "";
      //$i=0;
      foreach ($tabl as $k => $dline)
      {
        $fmas = str_replace("[@col" . $k . "]", $dline, $fmas);
      }
      ;
      $fmas = str_replace("[@row]", $nn + 1, $fmas);
      $fmas = str_replace("[@select]", $select, $fmas);
      $fmas = str_replace("[@style]", $getstyle, $fmas);

      $tabvalue .= preg_replace("/(\[@col)\d{1,2}\]/m", "", $fmas) . "\r";
      unset($fmas);
      $nn++;
    }

  }
  else
    $table_field_error = "������ ������ �������";

  return $tabvalue;
}

function se_db_unique_key($item, $key){
    $fl = true;
    $res = '';
    foreach($key as $it){
        if (empty($item[$it])){
            $fl = false;
        } else {
            if ($res) $res .= '-';
            $res .= $item[$it];
        }
    }
    if ($fl && $res){
        return "{$res}";
    }
}

function se_db_multi_perform($table, $data = array(), $key = 'id', $only_update = false, $link = 'db_link')
{
    global $$link;
    $keycount = 0;
    if (strpos($key, ',') !== false) {
        $key = explode(',', $key);
    } else {
        $key = array($key);
    }
    $keys = array();
    foreach ($data as $ii => $item) {
        $keys[] = "'" . se_db_unique_key($item, $key) . "'";
        foreach ($item as $fild => $value) {
            $fields[$ii][] = '`' . $fild . '`';
            if (is_numeric($value) || $value == 'null')
                $values[$ii][] = se_db_input($value);
            else $values[$ii][] = "'" . se_db_input($value) . "'";
        }
    }

    $skey = (count($key) > 1) ? "CONCAT_WS('-',`" . join('`,`', $key) . "`)" : "`{$key[0]}`";
    $where = "{$skey} IN (" . join(',', $keys) . ")";
    $update = array();
    if (!empty($keys)) {
        $request = "SELECT `id`,{$skey}  FROM `{$table}` WHERE $where  LIMIT " . count($keys);
        $req = mysqli_query($$link, $request);
        echo mysqli_error($$link);
        while ($line = mysqli_fetch_row($req)) {
            if (($k = array_search("'" . $line[1] . "'", $keys)) !== false) {
                $update[$line[1]] = $line[0];
                unset($keys[$k]);
            }
        }
    }
    $query = '';
    foreach ($data as $ii => $item) {
        $ikeys = se_db_unique_key($item, $key);
        if (!empty($update[$ikeys])) {
            $query .= "UPDATE `{$table}` SET ";
            $dats = '';
            foreach ($fields[$ii] as $id => $fld) {
                if (!empty($dats)) $dats .= ',';
                $dats .= $fld . '=' . $values[$ii][$id];
            }
            $query .= $dats . " WHERE `id`={$update[$ikeys]};\n";
        } elseif (!$only_update) {
            $query .= "INSERT INTO `{$table}`(" . join(',', $fields[$ii]) . ") VALUES (" . join(',', $values[$ii]) . ");\r\n";
        }
    }

    if (mysqli_multi_query($$link, $query)) {
        do {
        } while (mysqli_next_result($$link));
    }
    echo mysqli_error($$link);
    if (count($key) > 1) {
        //  echo "end\n"; //exit;
    }

    if (!empty($keys)) {
        $query = "SELECT `id`,{$skey} FROM `{$table}` WHERE {$skey} IN (" . join(',', $keys) . ");";
        $req = mysqli_query($$link, $query);
        echo mysqli_error($$link);
        $ids = array();
        while ($line = mysqli_fetch_row($req)) {
            $ids[$line[1]] = $line[0];
        }
    }
    foreach ($update as $key => $id) {
        $ids[$key] = $id;
    }
    return $ids;
}

function se_db_fields_item($table, $where, $select, $link = 'db_link')
{
  global $$link, $table_field_error;
  $itemval = "";
  //$varsel=explode(",",$select);
  $table = htmlspecialchars($table);
  $select = htmlspecialchars($select);
  if ($result = mysqli_query($$link,"select $select FROM $table WHERE $where"))
  {
    @$itemval = mysqli_fetch_array($result);
    if (count($itemval) < 3)
    {
      $itemval = $itemval[0];
    }
  }
  else
    $table_field_error = "������ ������ �������";
  return ($itemval);
}

function log_update($table, $id_rec = 0, $typech = 'i', $id_clman = 0, $id_log = 0, $fields = '', $link = 'db_link')
{
  global $$link;
  if ($typech != 'd' || strpos($table, 'stat_')!==false || strpos($table, 'search') !== false) return;
  if (!file_exists(SE_ROOT .'system/logs/log_synchro.upd'))
  {
    mysqli_query($$link,"CREATE TABLE IF NOT EXISTS `log_synchro` (
		`id` bigint NOT NULL auto_increment,
		`timesynchro` int(15),
		`tabl` varchar(40) NULL default '',
		`id_rec` bigint(20) default '0',
		`typech` char(1) default 'i',
		`id_clman` int(10) default '0',
		`id_log` bigint(20) default '0',
		`fields` varchar(255),
		 PRIMARY KEY (`id`),
		 KEY `timesynchro` (`timesynchro`),
		 KEY `tabl` (`tabl`),
		 KEY `id_rec` (`id_rec`),
		 KEY `typech` (`typech`)
		) ENGINE=MyISAM;");
	if (mysqli_error($$link) == ''){
		if (!is_dir(SE_ROOT .'system/logs')) mkdir(SE_ROOT .'system/logs');
		$fp = fopen(SE_ROOT .'system/logs/log_synchro.upd', "w+");
		fclose($fp);
	}
  }
  
  $deltime = time() - 86400 * 10;
  if ($typech == 'd') {
  	mysqli_query($$link,"DELETE FROM `log_synchro` WHERE `timesynchro`<'$deltime'");
  }
                              
  $timestamp = time();
  mysqli_query($$link,"INSERT INTO `log_synchro` (`tabl`,`id_rec`,`typech`,`timesynchro`,`id_clman`,`id_log`,`fields`)
    VALUES ('$table','$id_rec','$typech','" . $timestamp . "','$id_clman','$id_log', '$fields')");
}
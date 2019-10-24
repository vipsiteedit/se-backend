<?php
error_reporting(0);
$logaction = false;
header('Content-Type: application/octet-stream');
require_once("../system/config_db.php"); 
//error_reporting(125);
$half=join("",file("../system/.rkey"));
$half1=@$_POST['half'];
$half2=substr($half,35,10);
$sk=substr($half,45,32);


if (md5($half1.$half2)!=$sk) exit('no');
$data = 'OK:';


function log_update_table($table,$id_rec=0,$typech='i',$id_clman=0,$id_log=0){

     if (@mysql_num_rows(mysql_query("SHOW COLUMNS FROM `log_update`"))==0) {
      mysql_query("CREATE TABLE `log_update` (
	        `id` int(11) NOT NULL auto_increment,
		`tabl` varchar(40) NULL default '',
		`id_rec` bigint(20) default '0',
		`typech` char(1) default 'i',
		`timesynchro` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
		`id_clman` int(11) default '0',
		`id_log` bigint(20) default '0',
		 PRIMARY KEY  (`id`)
		) TYPE=MyISAM;");
    };
    $timestamp=date('Y-m-d H:i:s');
    mysql_query("DELETE FROM `log_update` WHERE `tabl`='$table' AND `id_rec`='$id_rec' AND `typech`<>'d'");
    mysql_query("INSERT INTO `log_update` (`tabl`,`id_rec`,`typech`,`timesynchro`,`id_clman`,`id_log`)
    VALUES ('$table','$id_rec','$typech','".$timestamp."','$id_clman','$id_log')");
}

function err( $error )
{
  die("err: $error");
}

function myerr()
{
  $e = mysql_error();
  mysql_free_result( mysql_query( 'ROLLBACK' ) );
  err( $e );
}

function pgerr( $err )
{
  $e = pg_last_error();
  if( $e == '' ) $e = $err;
  if( $e == '' ) $e = 'unknown pgsql error';
  pg_free_result( pg_query( 'ROLLBACK' ) );
  err( $e );
}


function dump( $val )
{
  global $data;
  $len = strlen($val);
  if( $len < 255 )
    $data .= chr($len);
  else
    $data .= "\xFF".pack('V',$len);
  $data .= $val;
  if( strlen($data) > 100000 ) {
    echo $data;
    $data = '';    
  }

}


if( get_magic_quotes_gpc()) 
  foreach( $_POST as $key => $value ) 
    $_POST[$key] = stripslashes($value);


  $commit = array_key_exists('commit',$_POST);
  $id_clman = round($_POST['clientman']);
  if (isset($_POST['list_id']))
  {
    $list_id = explode(',', $_POST['list_id']);
  } else $list_id = array();
  
  $conn = mysql_connect(HostName,DBUserName,DBPassword) or myerr();

  if(DBName != '' )
    mysql_select_db(DBName) or myerr();

    mysql_query ("set character_set_client='cp1251'");
    mysql_query ("set character_set_results='cp1251'");
    mysql_query ("set collation_connection='cp1251_general_ci'");


  if( array_key_exists('charset',$_POST) && $_POST['charset'] != '' )
    mysql_query( 'SET NAMES \'' . $_POST['charset'] . '\'' ) or myerr();
  $result = FALSE;
  mysql_free_result( mysql_query( 'BEGIN' ) );


  $data = 'OK:';
  for( $rn = 1; $rn < 1000; ++$rn ) 
  {
    if( !array_key_exists( 'r'.$rn, $_POST ) )
      break;

    $req = $_POST['r'.$rn];
      $ff=fopen("err.log","w+");
      fputs($ff,$req);
      fclose($ff);

    if( $req == 'connect' ) {
      dump( mysql_get_server_info() );
      dump( mysql_get_client_info() );
      dump( mysql_get_proto_info() );
      dump( mysql_get_host_info() );
    } else {
      $sql=$req;

    if (preg_match("/DELETE FROM(.+?)WHERE([\w\W\S\s\d]{1,})/im",$sql,$res_math)) {
	$table=trim(str_replace('`','',@$res_math[1]));
	if ($table!='session' || $table!='log_update') {
    	    $where=@$res_math[2];
	    $sqq="select id from $table where $where";
	    $res=mysql_query("select id from $table where $where");
	    if (!empty($res))
	    while (@$id_rec = mysql_fetch_array($res)) log_update_table($table,$id_rec[0],'d',$id_clman);
	}
    }

     if (preg_match("/INSERT INTO(.+?)[\W]{1,}\(/im", $sql, $res_math)) 
     {
        if (!empty($list_id))
        {
    	    $id_log = $list_id[$rn - 1];
    	    if (mysql_num_rows(mysql_query("SELECT * FROM `log_update` WHERE `id_log`={$id_log} AND tabl='{$res_math[1]}'"))>0) {
		$data=substr($data, 0, -1);
		$data.= $id_log;
	        continue;
	    }
        } 
     }

     $result = mysql_query($req) or myerr();

        //  myerr();
     // };  
      if( $result === TRUE ) {

        dump( 0 );
        dump( mysql_affected_rows() );
      } else {
        $width = mysql_num_fields($result);
        $height = mysql_num_rows($result);
        dump($width);
        dump($height);
        for( $i = 0; $i < $width; ++$i ) {
          dump( mysql_field_name( $result, $i ) );
          $type = mysql_field_type( $result, $i );
          $len = mysql_field_len( $result, $i );
          $meta = mysql_fetch_field( $result, $i );
          $sflags = explode( ' ', mysql_field_flags ( $result, $i ) );
          $fl = 0;
          if( $meta->not_null ) $fl += 1;
          if( $meta->primary_key ) $fl += 2;
          if( $meta->unique_key ) $fl += 4;
          if( $meta->multiple_key ) $fl += 8;
          if( $meta->blob ) $fl += 16;
          if( $meta->unsigned ) $fl += 32;
          if( $meta->zerofill ) $fl += 64;
          if( in_array( 'binary', $sflags ) ) $fl += 128;
          if( in_array( 'enum', $sflags ) ) $fl += 256;
          if( in_array( 'auto_increment', $sflags ) ) $fl += 512;
          if( in_array( 'timestamp', $sflags ) ) $fl += 1024;
          if( in_array( 'set', $sflags ) ) $fl += 2048;
          if( $type == 'int' ) {
            if( $len > 11 ) $type = 8;                    # LONGLONG
            elseif( $len > 9 ) $type = 3;                 # LONG
            elseif( $len > 6 ) $type = 9;                 # INT24
            elseif( $len > 4 ) $type = 2;                 # SHORT
            else $type = 1;                               # TINY
          } elseif( $type == 'real' ) {
            if( $len == 12 ) $type = 4;                   # FLOAT     
            elseif( $len == 22 ) $type = 5;               # DOUBLE
            else $type = 0;                               # DECIMAL
          } elseif( $type == 'null' ) $type = 6;          # NULL
          elseif( $type == 'timestamp' ) $type = 7;       # TIMESTAMP
          elseif( $type == 'date' ) $type = 10;           # DATE
          elseif( $type == 'time' ) $type = 11;           # TIME
          elseif( $type == 'datetime' ) $type = 12;       # DATETIME
          elseif( $type == 'year' ) $type = 13;           # YEAR
          elseif( $type == 'blob' ) {
            if( $len > 65536 ) $type = 251;               # LONG BLOB
            elseif( $len > 255 ) $type = 252;             # BLOB
            else $type = 249;                             # TINY BLOB
          } elseif( $type == 'string' ) $type = 253;       # VARCHAR
          else 
            $type = 252;
          dump( $type );
          dump( $fl );
          dump( $len );
        }
        for( $i = 0; $i < $height; ++$i ) {
          $row = mysql_fetch_row( $result );
          for( $j = 0; $j < $width; ++$j ) 
            if( is_null($row[$j]) ) 
              dump( '' );
            else
              dump( ' '.$row[$j] );
        }
        mysql_free_result( $result );
      }
    }




   if (substr($data,0,3)=='OK:') {

     if (preg_match("/INSERT INTO(.+?)[\W]{1,}\(/im",$sql,$res_math)) {
	$table=trim(str_replace('`','',@$res_math[1]));
	if ($table!='session' || $table!='log_update') {
        list($res)=mysql_fetch_array(mysql_query("SELECT LAST_INSERT_ID()"));
	 $data=substr($data,0,-1);
	 $data.=$res;


    	$id_log = $list_id[$rn - 1];
        log_update_table($table,$res,'i',$id_clman, $id_log);
        if ($table == 'shop_tovarorder') $logaction = true;
        
	}
     }
     if (preg_match("/UPDATE (.+?) SET[\w\W]{1,}where([\w\W\S\s\d]{1,})/im",$sql,$res_math)) {
	$table=trim(str_replace('`','',@$res_math[1]));
	if ($table!='session' || $table!='log_update') {
    	    $where=@$res_math[2];
	    $sqq="select id from $table where $where";
	    $res=mysql_query("select id from $table where $where");
	    if (!empty($res))
	    while (@$id_rec = mysql_fetch_array($res)) log_update_table($table,$id_rec[0],'u',$id_clman);
        if ($table == 'shop_tovarorder') $logaction = true;
	}    
     }  
   }
  }
$pf = fopen("log.db","a+");
fwrite($pf, $commit."\r\n\r\n");
fclose($pf);

  mysql_free_result( mysql_query( $commit ? 'COMMIT' : 'COMMIT' ) );
 // mysql_free_result( mysql_query( $commit ? 'COMMIT' : 'ROLLBACK' ) );


if( $data != '' ) echo $data;

if ($logaction) 
    include dirname(__FILE__).'/startaction.php';

?>
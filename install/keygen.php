<?php
error_reporting(E_ALL);
define ("STR", "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz");


function setpassword($Password) {
   $asv ='C6d7D8e9EhfHF0a1A2b3B4c5uUgGsSiIoOpPwWj!@#$%^&*()_+={}|\\/,.><?"\'';
  $d = '';
  for ($i = 0; $i < strlen($Password); $i++) {
    mt_srand();
    $r = (20 + mt_rand(0, 43));
    $r1 = floor(ord(substr($Password, $i,1)) / $r);
    $r2 = floor(ord(substr($Password, $i,1)) % $r);
    $d .= (substr($asv, $r, 1) . substr($asv, $r1, 1) . substr($asv, $r2, 1));
  }

  return $d;
}

mt_srand();

$s = '';
for ($i=1; $i<21; $i++) {
    $s.=substr(STR, mt_rand(0, 61), 1);
}

$half1 = substr($s, 0, 10);
$half2 = substr($s, 10, 10);
$secret_key = md5($s);
if (!empty($_SERVER['REMOTE_ADDR'])){
    $ip = $_SERVER['REMOTE_ADDR'];
} else {
    $ip = '127.0.0.0';
}

$ver = '7';
$ver = sprintf("%03d", $ver);
$domain = $_SERVER['HTTP_HOST'];
$client='admin';
$serial='0000000000';
$dateK = date("d.m.Y", mktime(0,0,0, date('m') + 120, date('d'), date('Y')));

//$key =  "4{$serial}".sprintf("%03d", $ver).str_repeat("0",10-strlen($half1)).$half1.$dateK.md5($ip)."\t{$domain}\t{$client}\t\t{$half2}{$secret_key}";
$key = "4".$serial.$ver.str_repeat("0",10-strlen($half1)).$half1.$dateK.md5($ip).
   chr(9).$domain.chr(9).$client.chr(9).chr(9).$half2.$secret_key;

$summ = 0;
for ($i = 0; $i < strlen($key); $i++) {
   $summ += ord(substr($key, $i, 1));
}

$nkey = setpassword($key . chr(8) . $summ);

$rkey = $ver.md5($domain).$half2.$secret_key;

file_put_contents(__DIR__ . '/.rkey', $rkey);
file_put_contents(__DIR__ . '/regkey.key', $nkey);
$file = __DIR__ . '/regkey.key';

if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($file).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    unlink($file);
    rename(__FILE__, __DIR__. '/../../' . basename(__FILE__));
    exit;
}

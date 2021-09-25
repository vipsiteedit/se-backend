<?php
ini_set('display_errors', 'on');
require 'system/main/inc.php';
error_reporting(E_ERR);

$domain = _HTTP_ . $_SERVER['HTTP_HOST'];
$file_turbo = SE_ROOT . SE_DIR . 'turbo_' . $_SERVER['HTTP_HOST'] . '.yml';

if (!file_exists($file_turbo) || filemtime($file_turbo) + 0 < time()) {
    //chdir(__DIR__);
    include_once('system/main/classes/seTurbo.class.php');
    new yaTurboPage(SE_DIR, $file_turbo);
}
//exit();
header("Content-type: text/xml");
echo join('', file($file_turbo));
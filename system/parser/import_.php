#! /usr/bin/php
<?php

session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);


require_once __DIR__ . '/lib/inc.php';

$grlist = glob(__DIR__ . '/data/*.group');
$groups = array();
foreach($grlist as $file) {
    $list = file($file);
    foreach($list as $item) {
        
        $path = str_replace("\t", '/', $item);
        $groups[] = array(
            'path'=>$path
        );
    }
}

//print_r($offers);
$import = new Import();
$import->importGroups($groups);
exit;


foreach($groups as $group) {
    $fname = __DIR__ . '/data/'.$group['code'].'.json';
    if (file_exists($fname)) {
        $offers = json_decode(file_get_contents($fname), true);
        $import->importOffers($offers);
    }
}

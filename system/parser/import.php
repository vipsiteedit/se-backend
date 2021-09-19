#! /usr/bin/php
<?php

session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);


require_once __DIR__ . '/lib/inc.php';

function setGroups()
{
    if (file_exists(__DIR__ . '/data/groups.json')) {
        $groups = json_decode(file_get_contents(__DIR__ . '/data/groups.json'), true);
        //print_r($offers);
        $import = new Import();
        $import->importGroups($groups);
    }
}

function setOffers()
{
    $offerfiles = glob(__DIR__ . '/data/offers/*.json');
    $import = new Import();
    foreach($offerfiles as $file) {
        $offers = json_decode(file_get_contents($file), true);
        $import->importOffers($offers);
    }
}

function setGeo()
{
    if (file_exists(__DIR__ . '/data/geo.json')) {
        $groups = json_decode(file_get_contents(__DIR__ . '/data/geo.json'), true);
        //print_r($groups);
        $import = new Import();
        $import->importRegions($groups);
    }
}

setGroups();
//setOffers();
//setGeo();

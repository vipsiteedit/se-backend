<?php

chdir(__DIR__ . "/../../../");

define('SE_INDEX_INCLUDED', true);
define('API_ROOT', getcwd());
define('IMPORT_LIB', __DIR__);

define('DIR_DATA', 'data');
define('PATH_DATA', API_ROOT . DIR_DATA . "/");
define('MAX_COUNT', 100);
define('DIR_IMAGES', API_ROOT .'/images/rus/shopprice');
define('DIR_GROUPIMAGES', API_ROOT .'/images/rus/shopgroup');
define('DIR_FILES', API_ROOT .'/files');

require_once IMPORT_LIB .'/import.class.php';
require_once IMPORT_LIB .'/export.class.php';
//require_once IMPORT_LIB .'/parser.php';
require_once IMPORT_LIB .'/db.php';
require_once IMPORT_LIB .'/function.php';
require_once API_ROOT . '/lib/lib_function.php';
require_once API_ROOT . '/lib/lib_utf8.php';

if (empty($CONFIG))
    require(API_ROOT . '/system/config_db.php');
DB::initConnection($CONFIG);
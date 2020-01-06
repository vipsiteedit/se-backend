<?php
//ini_set('display_errors', 1);
//error_reporting(E_ALL);
ini_set('display_errors', 'off');
error_reporting(0);
date_default_timezone_set('Europe/Moscow');
define('SE_INDEX_INCLUDED', true);
require 'system/main/init.php';
correct();
$file_market = SE_ROOT . 'market_new.yml';
if (!file_exists($file_market) || filemtime($file_market) + 300 < time()) {
    //chdir(__DIR__);
    include_once('lib/plugins/plugin_shop/plugin_yandex_market.class.php');
    new yandex_market(true);
}
//exit();
header("Content-type: text/xml");
echo join('', file($file_market));

function correct()
{
    if (!file_exists('system/logs/market.dat')) {
        $main = new seTable('main');
        $main->select('id');
        $main->where("`lang`='rus'");
        $main->fetchOne();

        $shop = new seTable('shop_integration_parameter');
        $shop->select('*');
        $shop->where("`code`='isDelivery'");
        $shop->fetchOne();

        if ($shop->id) {
            $shop->update('value', "'1'");
            $shop->save();
        } else {
            $shop->insert();
            $shop->id_main = $main->id;
            $shop->code = 'isDelivery';
            $shop->value = '1';
            $shop->save();
        }
        unset($shop);

        $shop = new seTable('shop_integration_parameter');
        $shop->select('*');
        $shop->where("`code`='localDeliveryDays'");
        $shop->fetchOne();

        if ($shop->id) {
            $shop->update('value', "'0'");
            $shop->save();
        } else {
            $shop->insert();
            $shop->id_main = $main->id;
            $shop->code = 'localDeliveryDays';
            $shop->value = '0';
            $shop->save();
        }
        file_put_contents('system/logs/market.dat', '');
    }
}
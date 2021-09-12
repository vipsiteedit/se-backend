<?php

function getCurrencyValues($code = 'USD', $fl_one = true)
{
    $dir = SE_ROOT . '/data';
    if (!is_dir($dir)) mkdir($dir);
    if (!file_exists($dir . '/currency.json') || date('Y-m-d', filemtime($dir . '/currency.json')) < date('Y-m-d')) {
        // $curr = file_get_contents("http://www.cbr.ru/scripts/XML_daily.asp?date_req=" . date('d/m/Y'));
        $curr = file_get_contents('https://www.cbr-xml-daily.ru/daily.xml');
        $xml = simplexml_load_string($curr);
        if (!empty($xml)) {
            $json = json_encode($xml);
            $fp = fopen($dir . '/currency.json', "w+");
            fwrite($fp, $json);
            fclose($fp);
        }
    }
    if (file_exists($dir . '/currency.json')) {
        $json = join('', file($dir . '/currency.json'));
        $curr = json_decode($json, true);
        if ($fl_one) {
            foreach ($curr['Valute'] as $valute) {
                if ($valute['CharCode'] == $code) {
                    return $valute;
                }
            }
        } else {
            return $curr['Valute'];
        }
    }
}

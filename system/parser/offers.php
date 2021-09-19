<?php

$filelist = glob(__DIR__ . '/data/*.prod');

foreach($filelist as $file) {
    $data = file_get_contents($file);
    $datalist = explode('&&', $data);
    $offers = array();
    $group = basename($file, '.prod');
    foreach($datalist as $item) {
        $item = explode('##', $item);
        /*
            'values'=>'Группа#Наименование:measure#Каблук&'
        */
        $modifiacation = [
            'params'=>[
                [
                    'group'=>'Доставка',
                    'features'=>['Марка авто','Время']
                    'values'=> ['Каблук','1ч']
                ]
            ],
            'article'=>'',
            'price'=>null,
            'price_opt'=>null,
            'price_opt_corp'=>null,
            'price_purchase'=>null,
            'text'=>'',
            'images'=>[]
        );
        
        $offers[] = array(
            'groups'=>[$group],
            'article'=>null,
            'name'=>trim($item[0]),
            'images'=>[],
            'note'=>str_replace("\n", "<br>", trim($item[5])),
            'text'=>str_replace('><br>', '>', str_replace("\n", "<br>", trim($item[6]))),
            'features'=>[],
            'modification'=>[],
            'price'=>null,
            'price_opt'=>null,
            'price_opt_corp'=>null,
            'price_purchase'=>null,
            'title'=>trim($item[1]),
            'description'=>trim($item[3]),
            'keywords'=>trim($item[2]),
            'page_title'=>trim($item[4])
        );
        
    }
    if (!is_dir(__DIR__ . '/data/offers/')) mkdir(__DIR__ . '/data/offers/');
    file_put_contents(__DIR__ . '/data/offers/' . $group. '.json', json_encode($offers, JSON_UNESCAPED_UNICODE));
}



echo 'ok';

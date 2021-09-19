<?php
function writeLog($data)
{
    if (!is_string($data))
        $data = print_r($data, 1);

    $file = fopen(API_ROOT . "debug.log", "a+");
    $query = "$data" . "\n";
    fputs($file, $query);
    fclose($file);
}


function getContent($url, $data = null, $headers = [])
{
    if (!is_dir(__DIR__."/tmp")) mkdir(__DIR__."/tmp");
	$file_cache = __DIR__."/tmp/" . md5($url);

    if (file_exists($file_cache))
        return file_get_contents($file_cache);

    $cookie = "cookies.txt";

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_FAILONERROR, 1);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie);
    curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie);
    if ($data) {
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    if ($headers) {
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    }
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_2) AppleWebKit/601.3.9');
    $result = curl_exec($curl);

    if (curl_errno($curl)) {
        echo "\nОшибка в URL: {$url}\n";
        echo curl_error($curl);
        sleep(3);
        return null;
    }

    curl_close($curl);

    if (strlen($result) > 1024) {
        if ($file = fopen($file_cache, "w+")) {
			fputs($file, $result);
			fclose($file);
		}
    } else $result = null;

    sleep(3);

    return $result;
}

function getImage($dirImages, $url)
{
    $fileName = explode("/", trim($url, "/"));
    $fileName = array_pop($fileName);
	$result = $filePath = $dirImages . "/{$fileName}";

    if (file_exists($filePath) && getimagesize($filePath))
        return $fileName;

    $image = getContent($url);

    if (!empty($result)) {
        file_put_contents($filePath, $image);
        if (!getimagesize($filePath))
            unlink($filePath);
        else $result = $fileName;
    }

    return $result;
}

function getFile($dirFiles, $url)
{
    $fileName = array_pop(explode("/", trim($url, "/")));
    $result = $filePath = $dirFiles . "/{$fileName}";

    if (file_exists($filePath))
        return $fileName;

    $file = getContent($url);

    if (!empty($result) && !empty($file)) {
        file_put_contents($filePath, $file);
        $result = $fileName;
    }

    return $result;
}

function convert($html)
{
    $html = str_replace(
        '<meta charset="windows-1251">',
        '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>',
        $html
    );
    $html = str_replace(
        '<span class="Price__s">&nbsp;</span>',
        '',
        $html
    );
    $html = str_replace("<sup>", "", $html);
    $html = str_replace("</sup>", "", $html);
    return iconv('windows-1251', 'UTF-8', $html);
}

function translite_url($string)
{
    $string = str_replace(array('№', '%20', ',', '.', '!', '?', '&', '(', ')', '<', '>', '{', '}', ' ', '_', '/', "\\", '[', ']'), '-', $string);
    $string = str_replace(array('+'), array('-plus'), $string);

    $result = preg_replace("/[\s]/", '-', rus2translit($string));
    while (strpos($result, '--') !== false) {
        $result = str_replace('--', '-', $result);
    }
    if (strlen($result)) {
        if (substr($result, 0, 1) == '-') {
            $result = substr($result, 1, strlen($result) - 1);
        }
        if (substr($result, strlen($result) - 1, 1) == '-') {
            $result = substr($result, 0, strlen($result) - 1);
        }
    }
    $result = str_replace('hh', 'hkh', $result);
    return preg_replace('/[^a-zA-Z0-9_-]/i', '', $result);
}
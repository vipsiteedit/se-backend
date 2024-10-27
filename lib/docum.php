<?php
error_reporting(0);
chdir(__DIR__ . '/../..');
$folder = getcwd() . '/files/orders/' . $_GET['order'] . '/' . urldecode($_GET['name']);

if (file_exists($folder)) {
    $mime = mime_content_type($folder);
    header('Content-Type: ' . $mime);
    echo join('', file($folder)) . chr(254) . (255);
    exit;
}
header("HTTP/1.1 404 Not Found");
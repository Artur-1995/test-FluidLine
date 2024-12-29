<?php
include 'src/IndexCntroller.php';
include 'src/FileHandler.php';
include 'src/Logger.php';

use src\IndexCntroller;

$controller = new IndexCntroller();

if (php_sapi_name() !== 'cli') {
    include 'src/template/upload.php';
}

if (php_sapi_name() === 'cli') {
    $argv = $_SERVER['argv'];
    $filename = $argv[1] ?? 'russian.txt';
    return $controller($filename);
} elseif (isset($_FILES['file'])) {
    $filename = $_FILES['file']['name'];
}

return $controller($filename);

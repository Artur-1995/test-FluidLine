<?php
include 'src/IndexCntroller.php';
include 'src/FileHandler.php';

use src\IndexCntroller;

$controller = new IndexCntroller();

return $controller->handler();
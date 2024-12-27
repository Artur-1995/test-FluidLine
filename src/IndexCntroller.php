<?php

namespace src;

use Exception;

class IndexCntroller
{
    public function handler($argv = [])
    {
        try {
            if (php_sapi_name() !== 'cli') {
                include 'src/template/upload.php';
            }
            if (php_sapi_name() === 'cli') {
                $argv = $_SERVER['argv'];
                $filename = $argv[1] ?? 'russian.txt';
            } elseif (isset($_FILES['file'])) {
                $filename = $_FILES['file']['name'];
            }

            $handler = new FileHandler();
            $handler->processFile($filename);
        } catch (Exception $e) {
            Logger::log([__CLASS__, __FUNCTION__], $e);
        }
    }
}

<?php

namespace src;

use Exception;

class IndexCntroller
{
    public function handler()
    {
        try {
            if (php_sapi_name() !== 'cli') {
                include 'src/template/upload.php';
            }
            if (php_sapi_name() === 'cli') {
                // Запуск из CLI
                $filename = $argv[1];
            } elseif (isset($_FILES['file'])) {
                $filename = $_FILES['file']['name'];
            }
            $handler = new FileHandler();
            $handler->processFile($filename);
        } catch (Exception $e) {
            Logget::log([__CLASS__, __FUNCTION__], $e);
        }
    }
}

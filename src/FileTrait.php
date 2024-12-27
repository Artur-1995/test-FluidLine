<?php

namespace Src;

use Exception;

trait FileTrait
{
    // Метод для записи данных в файлы
    public function writeInFile($letters): void
    {
        try {
            foreach ($letters as $letter => $data) {
                $dir = "library/$letter";
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                }
                file_put_contents("$dir/words.txt", implode("\n", $data['words']));
                file_put_contents("$dir/count.txt", $data['count']);
            }
        } catch (Exception $e) {
            Logger::log([__CLASS__, __FUNCTION__], $e);
        }
    }

    // Метод для создания файла с необходимой кодировкой
    public function convertFile($filename, $from = 'windows-1252', $to = 'windows-1251'): ?string
    {
        try {
            $content = file_get_contents($filename);
            $currentEncoding = mb_detect_encoding($content, mb_list_encodings(), true);
            // Преобразуем строку из utf-8/latin1 в windows-1252
            $newContent = iconv($currentEncoding, $from, $content);
            // Преобразуем строку из однобайтной кодировки обратно в utf-8, выдав её за windows-1251
            $newContent = iconv($to, $currentEncoding, $newContent);
            $newFilename = 'russian_converted.txt';
            file_put_contents($newFilename, $newContent);

            return $newFilename;
        } catch (Exception $e) {
            Logger::log([__CLASS__, __FUNCTION__], $e);
        }
    }

    // Метод копирует загруженый файл текущую директорию
    public function copyUploadFile($filename): ?string
    {
        try {
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if ($ext === 'txt') {
                $content = file_get_contents($_FILES['file']['tmp_name']);
                $newfilename = $_FILES['file']['name'];
                file_put_contents($filename, $content);
            }

            return $newfilename;
        } catch (Exception $e) {
            Logger::log([__CLASS__, __FUNCTION__], 'wer');
            Logger::log([__CLASS__, __FUNCTION__], $e);
        }

        return null;
    }
}

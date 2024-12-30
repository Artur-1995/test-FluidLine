<?php

/**
 * Обработчик файла
 * 
 * PHP version 7.4.33
 * 
 * @author Avetisyan Artur <89254423508@mail.ru>
 */

namespace Src;

include "FileTrait.php";

use Exception;

/**
 * Класс для обработки файла
 * 
 * @throws Exception $e ошибка обработки файла
 */
class FileHandler
{
    use FileTrait;

    /**
     * Обработка включает в себя разбиение слов по первым буквам и 
     * дальнейшем распределении слов по файлам с названием первой буквы, 
     * в котором хранится два файла один
     * со списком слов начинающихся на эту букву, а в другом количество
     * вхождений этой буквы в свмисок слов из первого файла
     * 
     * @param string $filename название файла
     * 
     * @throws Exception $e ошибка обработки файла
     */
    public function handler(string $filename): void
    {
        try {
            if (isset($_FILES['file'])) {
                $filename = $_FILES['file']['name'];
                if (!$this->copyUploadFile($filename)) {
                    exit();
                }
            }

            if (!$this->checkCyrillicInFile($filename)) {
                $filename = $this->convertFile($filename);
            }

            if (file_exists($filename)) {
                $handle = fopen($filename, "r");
            }

            if ($handle) {
                $letters = [];

                while (($line = fgets($handle)) !== false) {
                    $line = trim($line);
                    $firstLetter = mb_strtolower(mb_substr($line, 0, 1));
                    $letterCount = mb_substr_count($line, $firstLetter);
                    if (!isset($letters[$firstLetter])) {
                        $letters[$firstLetter] = [
                            'count' => 0,
                            'words' => []
                        ];
                    }
                    $letters[$firstLetter]['count'] += $letterCount;
                    $letters[$firstLetter]['words'][] = $line;
                }
                fclose($handle);
                $this->writeInFile($letters);
                echo 'Обработка завершена' . PHP_EOL;
            }
        } catch (Exception $e) {
            Logger::log([__CLASS__, __FUNCTION__], $e);
        }
    }
}

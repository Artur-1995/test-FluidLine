<?php

namespace Src;

include "FileTrait.php";

use Exception;

// Класс для обработки файла
class FileHandler
{
    use FileTrait;
    public function processFile(string $filename, bool $encoding = true): void
    {
        try {
            if (isset($_FILES['file'])) {
                $filename = $_FILES['file']['name'];
                if (!$this->copyUploadFile($filename)) {
                    exit();
                }
            }

            if ($encoding) {
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
            }
        } catch (Exception $e) {
            Logger::log([__CLASS__, __FUNCTION__], $e);
        }
    }
}
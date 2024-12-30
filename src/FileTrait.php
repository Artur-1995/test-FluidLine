<?php


/**
 * Трейт для обработки файлов
 * 
 * PHP version 7.4.33
 * 
 * @author Avetisyan Artur <89254423508@mail.ru>
 */

namespace Src;

use Exception;

/**
 * Трейт с набором методов для реализации обработки текстового файла
 * в котором нарушена кодировка
 * 
 * @throws Exception $e ошибка обработки файла
 */
trait FileTrait
{
    /**
     * Метод для записи данных в файлы
     * 
     * @param array $letters массив с файловой структурой и данными
     * 
     * @throws Exception $e ошибка обработки файла
     * 
     * @return void запись завершена
     */
    public function writeInFile(array $letters): void
    {
        try {
            foreach ($letters as $letter => $data) {
                $dir = $this->createFolder($letter);
                file_put_contents(
                    "$dir/words.txt",
                    implode("\n", $data['words'])
                );
                file_put_contents("$dir/count.txt", $data['count']);
            }
        } catch (Exception $e) {
            Logger::log([__CLASS__, __FUNCTION__], $e);
        }
    }

    /**
     * Метод для создания папки
     * 
     * @param string $letter название папки
     */
    public function createFolder($letter): string
    {
        $dir = "library/$letter";
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        return $dir;
    }

    /**
     * Метод для конвертации данных
     * 
     * Создает файл с преобразованием кодировки
     * 
     * @param string $filename название файла 
     * @param string $to название корректной кодировки, 
     * по умолчанию отсутствует
     * 
     * @throws Exception $e ошибка при чтении или записи
     * 
     * @param string $newFilename название нового файла
     */
    public function convertFile(
        string $filename,
        string $to = ''
    ): ?string {
        try {
            $content = file_get_contents($filename);

            $newContent = $this->textConvert($content, $to);

            if ($this->checkCyrillic($newContent)) {
                $newFilename = 'russian_converted.txt';
                file_put_contents($newFilename, $newContent);
            }
            return $newFilename;
        } catch (Exception $e) {
            Logger::log([__CLASS__, __FUNCTION__], $e);
        }
    }

    /**
     * Метод копирует загруженый файл в текущую директорию
     * 
     * @param string $filename название файла 
     * 
     * @throws Exception $e ошибка при чтении или записи
     * 
     * @param null|string $newFilename название нового файла или null
     */
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
            Logger::log([__CLASS__, __FUNCTION__], $e);
        }

        return null;
    }

    /**
     * Метод проверят текст, является ли он кирилице или нет
     * 
     * @param string $content текст для проверки
     * 
     * @param bool результат проверки true/false
     */
    public function checkCyrillic(string $content): bool
    {
        return preg_match('/[\p{Cyrillic}]/u', $content);
    }

    /**
     * Метод проверят текста из файла, является ли он кирилице или нет
     * 
     * @param string $filename название файла 
     * 
     * @throws Exception $e ошибка при чтении
     * 
     * @param bool $this->checkCyrillic результат работы функции true/false
     */
    public function checkCyrillicInFile(string $filename): bool
    {
        if (is_file($filename)) {
            $content = file_get_contents($filename);
        }

        return $this->checkCyrillic($content);
    }

    /**
     * Метод конвертации текста
     * 
     * Метод исправляет кодировку для правелного отображения кирилицы
     * 
     * @param string $content текст для проверки
     * @param string $to название корректной кодировки
     * по умолчанию отсутствует
     * 
     * @throws Exception $e ошибка при чтении
     * 
     * @param bool $this->checkCyrillic результат работы функции true/false
     */
    public function textConvert(string $content, $to = '')
    {
        $currentEncoding = mb_detect_encoding(
            $content,
            mb_list_encodings(),
            true
        );
        $possibleEncodings = mb_list_encodings() + ['windows-1252'];
        foreach ($possibleEncodings as $encoding) {
            if (@iconv($currentEncoding, $encoding, $content)) {
                $newContent = iconv($currentEncoding, $encoding, $content);
                $newContent = iconv($to, $currentEncoding, $newContent);
                if ($this->checkCyrillic($newContent)) {
                    echo "Кодировка: $encoding \n";
                    echo "Текст успешно сконвертирован \n";
                    break;
                } else {
                    $this->textConvert($content, $to);
                }
            }
        }

        return $newContent;
    }
}

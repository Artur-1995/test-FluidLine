<?php

/**
 * Контроллер для обработки файла
 * 
 * PHP version 7.4.33
 * 
 * @author Avetisyan Artur <89254423508@mail.ru>
 */

namespace src;

use Exception;

/**
 * Класс для обработки запроса 
 * 
 * Класс может обработать файл из корневой директории
 * с названием russian.txt по умолчанию с помощью команды cli 
 * и файлы загруженные через форму на странице
 * 
 * PHP version 7.4.33
 * 
 * @author Avetisyan Artur <89254423508@mail.ru>
 * 
 * @throws Exception $e Исключения обработки
 */
class IndexCntroller
{
    public function __invoke(string $filename)
    {
        try {
            $fileHandler = new FileHandler();
            $fileHandler->handler($filename);
        } catch (Exception $e) {
            Logger::log([__CLASS__, __FUNCTION__], $e);
        }
    }
}

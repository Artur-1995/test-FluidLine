<?php

namespace Src;

use Stringable;

// метод для логирования ошибок
class Logger
{
    // метод для записи логов в файл
    public static function log(array $name, $e): void
    {
        $e = is_string($e) ? $e: $e->getCode() . ' ' . $e->getMessage();
        file_put_contents(
            'error.log',
            date(
                'm/d/Y H:i:s ',
                time()
            ) .
                " $name[0], $name[1] : $e\n",
            FILE_APPEND
        );
    }
}

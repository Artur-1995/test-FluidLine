<?php

/**
 * Файл с аблстрактным классом для взаимодейвствия с БД и его реализацией
 * 
 * PHP version 7.4.33
 * 
 * @author Avetisyan Artur <89254423508@mail.ru>
 */

use Src\Logger;

/**
 * Абстрактный класс с методами для взаимодейвствия с БД
 * 
 * PHP version 7.4.33
 * 
 * @author Avetisyan Artur <89254423508@mail.ru>
 */
abstract class DatabaseConnector
{
    /**
     * Интерфейс для работы с БД
     * @var PDO
     */
    public $pdo;

    /**
     * Подключение к БД
     *
     * @return void Интерфейст подключен
     */
    public function connect(): void
    {
        try {
            $this->pdo = new PDO("mysql:host=localhost;dbname=newdb", "root", "");
            $this->pdo->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
        } catch (PDOException $e) {
            Logger::log([__CLASS__, __FUNCTION__], $e);
        }
    }

    /**
     * Метод реализующий добавление данных, когда есть дейвствующее подключение к БД 
     *
     * @return void 
     */
    public function pushData(): void
    {
        if (!$this->connect()) {
            Logger::log([__CLASS__, __FUNCTION__], "Не удалось подключиться к базе данных.");
            return;
        }
        $this->create();
    }

    /**
     * Добавление данных 
     */
    abstract public function create();

    /**
     * Обновление данных 
     */
    abstract public function update($id);

    /**
     * Удаление данных 
     */
    abstract public function delete();

    /**
     * Метод поиска id записи
     * 
     * @param string $letters название таблицы
     * @param string $where параметры для поиска записи (часть sql запроса с условием)
     * 
     * @return ?string $id id записи в таблице letters | null при отсутствии записи
     */
    public function find(string $letters, string $where): ?string
    {
        try {
            $sql = "DELETE FROM $letters WHERE $where";
            $id = $this->pdo->query($sql);
        } catch (PDOException $e) {
            Logger::log([__CLASS__, __FUNCTION__], $e);
        }

        return $id;
    }

    /**
     * Метод для sql запросов к бд
     * 
     * @param string $sql запрос к бд
     * 
     * @return ?string $id id записи в таблице letters | null при отсутствии записи
     */
    public function requestSql(string $sql): ?string
    {
        try {
            $result = $this->pdo->query($sql);
        } catch (PDOException $e) {
            Logger::log([__CLASS__, __FUNCTION__], $e);
        }

        return $result;
    }
}

class MockDatabaseConnector extends DatabaseConnector
{
    /**
     * Первая буква в слове
     * @var string
     */
    public $firstLetter;

    /**
     * Слово
     * @var string
     */
    public $word;

    /**
     * Кол-во вхождений первой буквы в слове
     * @var string
     */
    public $firstLetterCount;

    /**
     * Метод для присвоения значений свойствам класса
     * 
     * @param array $data массив содержащий данные для присвоения переменным
     */
    public function cunstruct($data): void
    {
        $this->firstLetter = $data['firstLetter'];
        $this->word = $data['word'];
        $this->firstLetterCount = $data['firstLetterCount'];
    }

    /**
     * Метод создания записи
     */
    public function create(): void
    {
        try {
            $sql = "INSERT INTO letters (first_letter, word, first_letter_count) VALUES ($this->firstLetter, $this->word, $this->firstLetterCount)";
            $this->pdo->query($sql);
        } catch (PDOException $e) {
            Logger::log([__CLASS__, __FUNCTION__], $e);
        }
    }

    /**
     * Метод обновления записим
     * 
     * @param $id id записи в таблице letters
     */
    public function update($id = ''): void
    {
        try {
            $sql = "UPDATE letters SET first_letter=$this->firstLetter, word=$this->word, first_letter_count=$this->firstLetterCount WHERE id=$id";
            $this->pdo->query($sql);
        } catch (PDOException $e) {
            Logger::log([__CLASS__, __FUNCTION__], $e);
        }
    }

    /**
     * Метод удаления записи
     * 
     * @param $id id записи в таблице letters
     */
    public function delete($id = ''): void
    {
        try {
            $sql = "DELETE FROM letters WHERE id=$id ";
            $this->pdo->query($sql);
        } catch (PDOException $e) {
            Logger::log([__CLASS__, __FUNCTION__], $e);
        }
    }
}

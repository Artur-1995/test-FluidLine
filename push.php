<?php

use Src\Logger;

abstract class DatabaseConnector {
    public $pdo; 
    public function connect()
    {
        try { 
            $this->pdo = new PDO("mysql:host=localhost;dbname=newdb", "root", ""); 
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, 
                                PDO::ERRMODE_EXCEPTION); 
        } 
        catch (PDOException $e) { 
            Logger::log([__CLASS__, __FUNCTION__], $e);
        }
    }
    abstract protected function pushData();
    abstract public function create();
    abstract public function update($id);
    abstract public function delete();
}

class MockDatabaseConnector extends DatabaseConnector {

   public $firstLetter;
   public $word;
   public $firstLetterCount;
    public function cunstruct($data): void
    {
        $this->firstLetter = $data['firstLetter'];
        $this->word = $data['word'];
        $this->firstLetterCount = $data['firstLetterCount'];
    } 

    // метод реализующий вызов и отключение, когда подключение к базе отсутствует
    protected function pushData() {
        if (!$this->connect()) {
            echo "Не удалось подключиться к базе данных.";
            return;
        }
        $this->create();
    }
    
    // Метод создания записи
    public function create()
    {
        try {
            $sql = "INSERT INTO letters (first_letter, word, first_letter_count) VALUES ($this->firstLetter, $this->word, $this->firstLetterCount)";
            $this->pdo->query($sql);
        } catch (PDOException $e) {
            Logger::log([__CLASS__, __FUNCTION__], $e);
        }
    }

    // Метод обновления записи
    public function update($id = ''): void
    {
        try {
            $sql = "UPDATE letters SET first_letter=$this->firstLetter, word=$this->word, first_letter_count=$this->firstLetterCount WHERE id=$id";
            $this->pdo->query($sql);
        } catch (PDOException $e) {
            Logger::log([__CLASS__, __FUNCTION__], $e);
        }
    }

    // Метод удаления записи
    public function delete(): void
    {
        try {
            $sql = "DELETE FROM letters WHERE first_letter=$this->firstLetter, word=$this->word, first_letter_count=$this->firstLetterCount ";
            $this->pdo->query($sql);
        } catch (PDOException $e) {
            Logger::log([__CLASS__, __FUNCTION__], $e);
        }
    }
}
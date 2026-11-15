<?php
class Database
{
    private string $host     = 'localhost';
    private string $dbName   = 'wellness_clinicdb'; 
    private string $username = 'root';               
    private string $password = '';                  

    public function connect(): PDO
    {
        return new PDO(
            "mysql:host={$this->host};dbname={$this->dbName};charset=utf8mb4",
            $this->username,
            $this->password,
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    }
}

class DBRepository
{
    protected static function db(): PDO
    {
        return (new Database())->connect();
    }
}
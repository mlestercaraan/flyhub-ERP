<?php
declare(strict_types=1);

class Database
{
    private static ?Database $instance = null;
    private mysqli $connection;
    
    private string $host = 'localhost';
    private string $username = 'root';
    private string $password = '';
    private string $database = 'flyhub_erp';
    
    private function __construct()
    {
        $this->connection = new mysqli(
            $this->host,
            $this->username,
            $this->password,
            $this->database
        );
        
        if ($this->connection->connect_error) {
            throw new Exception('Database connection failed: ' . $this->connection->connect_error);
        }
        
        $this->connection->set_charset('utf8mb4');
    }
    
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    
    public function getConnection(): mysqli
    {
        return $this->connection;
    }
    
    public function __destruct()
    {
        if ($this->connection) {
            $this->connection->close();
        }
    }
}
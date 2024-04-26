<?php

namespace LeanPHP\Config;

use PDO;
use PDOException;
use LeanPHP\Core\Logger;

class DatabaseManager
{
    private static $instance = null;
    private $connection;
    private $logger;

    public function __construct()
    {
        $this->logger = new Logger();
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function connect()
    {
        if ($this->connection === null) {

            $driver = getenv('DB_DRIVER');
            $host = getenv('DB_HOST');
            $port = getenv('DB_PORT');
            $dbname = getenv('DB_DATABASE');
            $username = getenv('DB_USERNAME');
            $password = getenv('DB_PASSWORD');
    
            try {
                $this->connection = new PDO(
                    "{$driver}:host={$host};port={$port};dbname={$dbname}",
                    $username,
                    $password,
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                );
            } catch (PDOException $e) {
                $this->handleException($e);
            }
        }
    }

    public function getConnection()
    {
        $this->connect();
        return $this->connection;
    }

    private function handleException(PDOException $e)
    {
        print_r("Database error: " . $e->getMessage());
        echo "An error occurred. Please try again later.";
        exit();
    }
}

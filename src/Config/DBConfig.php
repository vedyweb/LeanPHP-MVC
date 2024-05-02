<?php

namespace LeanPHP\Config;

use PDO;
use PDOException;
use LeanPHP\Core\Logger;

class DBConfig
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

    public function connect()
    {
        if ($this->connection === null) {
            $driver = getenv('DB_DRIVER');  // 'sqlite', 'pgsql', 'mysql', 'oci', 'mongodb'

            switch ($driver) {
                case 'sqlite':
                    $this->connectSQLite();
                    break;
                case 'pgsql':
                    $this->connectPostgres();
                    break;
                case 'mysql':
                    $this->connectMySQL();
                    break;
                case 'oci':
                    $this->connectOracle();
                    break;
                default:
                    $this->handleException(new PDOException("Unsupported driver: $driver"));
                    break;
            }
        }
    }

    private function connectSQLite()
    {
        $path = getenv('DB_PATH');
        try {
            $this->connection = new PDO("sqlite:$path");
        } catch (PDOException $e) {
            $this->handleException($e);
        }
    }

    private function connectPostgres()
    {
        $dsn = "pgsql:host=" . getenv('DB_HOST') . ";port=" . getenv('DB_PORT') . ";dbname=" . getenv('DB_NAME');
        try {
            $this->connection = new PDO($dsn, getenv('DB_USER'), getenv('DB_PASSWORD'), [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        } catch (PDOException $e) {
            $this->handleException($e);
        }
    }

    private function connectMySQL()
    {
        $dsn = "mysql:host=" . getenv('DB_HOST') . ";dbname=" . getenv('DB_NAME') . ";charset=utf8mb4";
        try {
            $this->connection = new PDO($dsn, getenv('DB_USER'), getenv('DB_PASSWORD'), [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        } catch (PDOException $e) {
            $this->handleException($e);
        }
    }

    private function connectOracle()
    {
        $dsn = "oci:dbname=" . getenv('DB_NAME') . ";charset=UTF8";
        try {
            $this->connection = new PDO($dsn, getenv('DB_USER'), getenv('DB_PASSWORD'), [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        } catch (PDOException $e) {
            $this->handleException($e);
        }
    }

    public function getConnection()
    {
        $this->connect();
        return $this->connection;
    }

    private function handleException($e)
    {
        print_r("Database error: " . $e->getMessage());
        echo "An error occurred. Please try again later.";
        exit();
    }
}
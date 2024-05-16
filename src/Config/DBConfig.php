<?php

namespace LeanPHP\Config;

use PDO;
use PDOException;
use LeanPHP\Core\Logger;
use LeanPHP\Core\ErrorHandler;

class DBConfig
{
    private static $instance = null;
    private $connection;

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
            $driver = getenv('DB_DRIVER');  // 'sqlite', 'pgsql', 'mysql', 'oci'

            try {
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
                        throw new PDOException("Unsupported driver: $driver");
                }
            } catch (PDOException $e) {
                $this->handleException($e);
            }
        }
    }

    private function connectSQLite()
    {
        $path = getenv('DB_PATH');
        $this->connection = new PDO("sqlite:$path");
        Logger::logInfo("Connected to SQLite database at $path");
    }

    private function connectPostgres()
    {
        $dsn = "pgsql:host=" . getenv('DB_HOST') . ";port=" . getenv('DB_PORT') . ";dbname=" . getenv('DB_NAME');
        $this->connection = new PDO($dsn, getenv('DB_USER'), getenv('DB_PASSWORD'), [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        Logger::logInfo("Connected to PostgreSQL database at " . getenv('DB_HOST'));
    }

    private function connectMySQL()
    {
        $dsn = "mysql:host=" . getenv('DB_HOST') . ";dbname=" . getenv('DB_NAME') . ";charset=utf8mb4";
        $this->connection = new PDO($dsn, getenv('DB_USER'), getenv('DB_PASSWORD'), [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        Logger::logInfo("Connected to MySQL database at " . getenv('DB_HOST'));
    }

    private function connectOracle()
    {
        $dsn = "oci:dbname=" . getenv('DB_NAME') . ";charset=UTF8";
        $this->connection = new PDO($dsn, getenv('DB_USER'), getenv('DB_PASSWORD'), [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        Logger::logInfo("Connected to Oracle database with name " . getenv('DB_NAME'));
    }

    public function getConnection()
    {
        $this->connect();
        return $this->connection;
    }

    private function handleException($e)
    {
        Logger::logError($e);
        ErrorHandler::handle($e);
    }
}
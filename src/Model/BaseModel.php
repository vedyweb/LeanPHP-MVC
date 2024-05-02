<?php

namespace LeanPHP\Model;
use LeanPHP\Core\ErrorHandler;
use PDO;
use PDOException;
use Exception;
use LeanPHP\Config\DBConfig;

class BaseModel {

    protected $db;
    protected $table;
    private $errorHandler;

    public function __construct() {
        $this->errorHandler = new ErrorHandler();
    }

    public function getDb() {
        if (!$this->db) {
            $databaseManager = new DBConfig();
            $this->db = $databaseManager->getConnection();
        }
        return $this->db;
    }

    protected function executeQuery(string $query, array $params = [], bool $fetchAll = true) {
        try {
            $stmt = $this->getDb()->prepare($query);
            foreach ($params as $key => &$value) {
                $stmt->bindParam(":$key", $value);
            }
            $stmt->execute();
            return $fetchAll ? $stmt->fetchAll(PDO::FETCH_ASSOC) : $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->errorHandler->handle($e);
            throw new Exception("Database error during query execution: " . $e->getMessage());
        }
    }

    // Burada da çalışır direkt
    public function search(string $searchTerm, string $field = 'name'): array {
        $query = "SELECT * FROM $this->table WHERE $field LIKE :searchTerm";
        return $this->executeQuery($query, ['searchTerm' => "%$searchTerm%"]);
    } 
}
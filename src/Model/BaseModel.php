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
            $this->errorHandler->handle($e->getMessage());
            throw new Exception("Database error during query execution.");
        }
    }
    
    public function getAll(): array {
        return $this->executeQuery("SELECT * FROM $this->table");
    }

    public function getById(int $id): ?array {
        $query = "SELECT * FROM $this->table WHERE id = :id";
        $result = $this->executeQuery($query, ['id' => $id], false);
        return $result ?: ['error' => true, 'message' => 'Record not found'];
    }

    public function create(array $data): bool {
        $keys = array_keys($data);
        $fields = implode(", ", $keys);
        $placeholders = ":" . implode(", :", $keys);
        $query = "INSERT INTO $this->table ($fields) VALUES ($placeholders)";
        return $this->executeQuery($query, $data, false);
    }

    public function update(int $id, array $data): bool {
        $setString = '';
        foreach ($data as $key => $value) {
            $setString .= "$key = :$key, ";
        }
        $setString = rtrim($setString, ', ');
        $query = "UPDATE $this->table SET $setString WHERE id = :id";
        $data['id'] = $id;
        return $this->executeQuery($query, $data, false);
    }

    public function delete(int $id): bool {
        $query = "DELETE FROM $this->table WHERE id = :id";
        return $this->executeQuery($query, ['id' => $id], false);
    }

    public function search(string $searchTerm, string $field = 'name'): array {
        $query = "SELECT * FROM $this->table WHERE $field LIKE :searchTerm";
        return $this->executeQuery($query, ['searchTerm' => "%$searchTerm%"]);
    }
}

?>

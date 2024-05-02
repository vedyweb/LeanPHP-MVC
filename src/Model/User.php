<?php

namespace LeanPHP\Model;
use LeanPHP\Core\ErrorHandler;
use PDO;
use PDOException;
use Exception;
use LeanPHP\Config\DBConfig;

//class User extends Model{
class User {

    protected $db;
    protected $table = 'users';
    private $errorHandler;

    public function __construct() {
        $this->errorHandler = new ErrorHandler();
    }

    public function getDb()
    {
        if (!$this->db) {
            $databaseManager = new DBConfig();
            $this->db = $databaseManager->getConnection();
        }
        return $this->db;
    }


    public function getAll(): array {
        $query = "SELECT * FROM $this->table";
        try {
            $stmt = $this->getDb()->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->errorHandler->handle($e->getMessage());
            throw new Exception("Database error while fetching all users.");
        }
    }

    public function getById(int $userId): ?array {
        $query = "SELECT * FROM $this->table WHERE user_id = :userId";
        try {
            $stmt = $this->getDb()->prepare($query);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result === false) {
                // Eğer sonuç yoksa, null yerine özel bir hata mesajı içeren bir dizi döndür
                return ['error' => true, 'message' => 'User not found'];
            }
            return $result;
        } catch (PDOException $e) {
            $this->errorHandler->handle($e->getMessage());
            throw new Exception("Database error while fetching user by ID.");
        }
    }

    public function create(string $username, string $email, string $password): bool {
        $query = "INSERT INTO $this->table (username, email, password) VALUES (:username, :email, :password)";
        try {
            $stmt = $this->getDb()->prepare($query);
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            $this->errorHandler->handle($e->getMessage());
            throw new Exception("Database error while creating user.");
        }
    }

    public function update(int $userId, array $data): bool {
        $setString = '';
        foreach ($data as $key => $value) {
            $setString .= "$key = :$key, ";
        }
        $setString = rtrim($setString, ', ');

        $query = "UPDATE $this->table SET $setString WHERE user_id = :userId";
        try {
            $stmt = $this->getDb()->prepare($query);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            foreach ($data as $key => &$value) {
                $stmt->bindParam(":$key", $value);
            }
            return $stmt->execute();
        } catch (PDOException $e) {
            $this->errorHandler->handle($e->getMessage());
            throw new Exception("Database error while updating user.");
        }
    }

    public function delete(int $userId): bool {
        $query = "DELETE FROM $this->table WHERE user_id = :userId";
        try {
            $stmt = $this->getDb()->prepare($query);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            $this->errorHandler->handle($e->getMessage());
            throw new Exception("Database error while deleting user.");
        }
    }

    public function search(string $username): array {
        $query = "SELECT * FROM $this->table WHERE username LIKE :username";
        try {
            $stmt = $this->getDb()->prepare($query);
            $username = "%$username%";
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->errorHandler->handle($e->getMessage());
            throw new Exception("Database error while searching users.");
        }
    }

    public function count(): int {
        $query = "SELECT COUNT(*) FROM $this->table";
        try {
            $stmt = $this->getDb()->query($query);
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            $this->errorHandler->handle($e->getMessage());
            throw new Exception("Database error while counting users.");
        }
    }

    public function filter(array $criteria): array {
        $query = "SELECT * FROM $this->table WHERE 1=1";
        $params = [];
    
        foreach ($criteria as $key => $value) {
            if ($value !== null) {
                $query .= " AND $key LIKE :$key";
                $params[$key] = "%$value%";
            }
        }
    
        try {
            $stmt = $this->getDb()->prepare($query);
            foreach ($params as $key => &$value) {
                $stmt->bindParam(":$key", $value);
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->errorHandler->handle($e->getMessage());
            throw new Exception("Database error while filtering users.");
        }
    }
}    
